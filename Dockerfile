# Use a slim Python image to reduce the size of the final image
FROM python:3.9-slim

# Prevents Python from writing pyc files to disk
ENV PYTHONDONTWRITEBYTECODE=1

# Ensures stdout and stderr are printed directly
ENV PYTHONUNBUFFERED=1

# Set the working directory
WORKDIR /code

# Set the DEBIAN_FRONTEND environment variable to noninteractive to avoid prompts
ENV DEBIAN_FRONTEND=noninteractive

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    build-essential \
    libpq-dev \
    tmux \
    && rm -rf /var/lib/apt/lists/*

# Copy only the requirements file to leverage Docker cache for dependencies
COPY requirements.txt /code/

# Install Python dependencies
RUN pip install --no-cache-dir -r requirements.txt

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

# Copy the rest of the application code
COPY . /code/

# Collect static files
RUN python manage.py collectstatic --noinput

# Set entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
