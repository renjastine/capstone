import sys
import os
from flask import Flask

# Add the directory containing your Flask application to the Python path
sys.path.insert(0, 'C:/xampp/htdocs/capstone')

# Import your Flask application
from web import app as application  # Ensure 'app' is the Flask instance