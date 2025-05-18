import requests
import os

# Step 1: Get the list of page titles in the category
category_url = "http://wikiurl/api.php"
params = {
    "action": "query",
    "list": "categorymembers",
    "cmtitle": "selectedcat",
    "format": "json",
    "cmlimit": "max"  # Adjust limit as needed
}

response = requests.get(category_url, params=params)
category_members = response.json()

# Check if the query was successful and contains the expected data
if 'query' not in category_members or 'categorymembers' not in category_members['query']:
    print("Error: The query did not return the expected data.")
    exit()

page_titles = [member['title'] for member in category_members['query']['categorymembers']]

# Construct URLs for each page
base_url = "http://wikiurl/index.php?title="
page_urls = [base_url + title.replace(' ', '_') for title in page_titles]

# Ensure the output directory exists
output_dir = "wikiarchiveurls"
os.makedirs(output_dir, exist_ok=True)

# Save the list of URLs to a file
urls_file_path = os.path.join(output_dir, "page_urls.txt")
with open(urls_file_path, 'w') as urls_file:
    for url in page_urls:
        urls_file.write(url + "\n")

print("URLs have been saved to", urls_file_path)
