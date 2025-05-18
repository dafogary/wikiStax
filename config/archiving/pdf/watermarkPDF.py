import os
import re
from datetime import datetime

# Directories containing HTML files
html_directories = [
    'wikiarchivehtml',  # HTML files
]

# Style code for the watermark
watermark_style = """
<style>
    .watermark {
        font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
        font-size: 20px;
        font-style: normal;
        font-variant: normal;
        font-weight: 700;
        line-height: 21px;
        right: 0;  /* Aligns the watermark to the right */
        text-align: left;  /* Ensures text within the element is right-aligned */
        height: auto;
        z-index: -200;
        color: #ab020a;
    }
</style>
"""


for directory in html_directories:
    # Loop through HTML files in the directory
    for filename in os.listdir(directory):
        if filename.endswith('.html'):
            file_path = os.path.join(directory, filename)
            with open(file_path, 'r') as file:
                content = file.read()

            # Initialize updated_content with the original content
            updated_content = content

            # Inject the watermark style into the <head> tag
            updated_content = updated_content.replace('<head>', '<head>' + watermark_style)

            # Generate a timestamp
            current_time = datetime.now().strftime('%Y%m%d %H:%M:%S')

            # Insert the watermark with the dynamic timestamp
            watermark_code = f"""
<div class="watermark">This version of archive_title is uncontrolled.<br>It was generated on: {current_time}Z</div>
"""

            # The regular expression pattern to match a <body> tag
            pattern = r'<body[^>]*>'

            # Use re.sub() to replace the matched pattern with the watermark HTML
            updated_content = re.sub(pattern, lambda x: x.group() + watermark_code, updated_content)

            # Write the updated content back to the file
            with open(file_path, 'w') as file:
                file.write(updated_content)

            print(f"Injected code into {filename}")
