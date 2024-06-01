# transport/management/commands/inspect_transport_db.py
# Run with `docker-compose run web python manage.py inspect_transport_db`
import re
from django.core.management.base import BaseCommand
from django.core.management import call_command
from django.db import connection
from io import StringIO

class Command(BaseCommand):
    help = 'Inspects the database and generates models only for transport_ tables.'

    def handle(self, *args, **options):
        with connection.cursor() as cursor:
            cursor.execute("SELECT tablename FROM pg_tables WHERE schemaname = 'public' AND tablename LIKE 'transport_%'")
            tables = [row[0] for row in cursor.fetchall()]

        models_output = StringIO()
        call_command('inspectdb', *tables, stdout=models_output)

        models_content = models_output.getvalue()
        models_output.close()

        # Remove `Transport` prefix from model names and references
        models_content = re.sub(r'\bTransport([A-Z]\w*)\b', r'\1', models_content)
        models_content = re.sub(r'\'Transport([A-Z]\w*)\'', r'\'\1\'', models_content)
        models_content = re.sub(r'transport_', '', models_content)

        # Write the final models to transport/models.py
        with open('transport/models.py', 'w') as models_file:
            models_file.write(models_content)
