# transport/forms.py
from django import forms

class ReservationForm(forms.Form):
    action = forms.CharField(widget=forms.HiddenInput())
    scurrentdate = forms.CharField(widget=forms.HiddenInput())
    txtdeptdatetime = forms.DateField(widget=forms.HiddenInput())
