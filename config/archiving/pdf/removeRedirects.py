import os
from bs4 import BeautifulSoup

def find_and_remove_files(directory):
    # Iterate through all files and directories in the given directory
    for root, dirs, files in os.walk(directory):
        # Loop through all files in the current directory
        for filename in files:
            filepath = os.path.join(root, filename)
            # Check if the file is a HTML file
            if filename.endswith('.html'):
                with open(filepath, 'r', encoding='utf-8') as file:
                    # Read the HTML content
                    html_content = file.read()
                    # Parse the HTML using BeautifulSoup
                    soup = BeautifulSoup(html_content, 'html.parser')
                    # Find all <span> tags with class 'mw-redirectedfrom'
                    redirected_spans = soup.find_all('span', class_='mw-redirectedfrom')
                    # If any such <span> tags are found, remove the file
                    if redirected_spans:
                        print(f"Found redirected spans in file: {filename}")
                        os.remove(filepath)
                        print(f"File {filename} removed.")
                    else:
                        print(f"No redirected spans found in file: {filename}")

# Directory path
directory_path = 'wikiarchivehtml'

# Call the function to find and remove files
find_and_remove_files(directory_path)
