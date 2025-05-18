#!/bin/bash
#Copy Wiki

rm -R wikiarchiveprocessing // Remove the old processing directory
mkdir wikiarchiveprocessing // Making the new processing directory
mkdir wikiarchivehtml // Making the new HTML directory
mkdir wikiarchiveurls // Making the new getURLs directory

python3 wikiarchivedir/getURLs.py

# File containing the list of URLs
input_file="wikiarchiveurls/page_urls.txt"

# Directory where the HTML files will be saved
output_directory="wikiarchivehtml"

# Loop over each URL in the input file
wget --header="Accept-Charset: UTF-8" -i $input_file -P wikiarchivemd -E -H -k -K -p

# remove pages with redirect code

#not needed
#python3 wikiarchivedir/removeRedirects.py

find wikiarchivehtmlurl -type f -name "*.html" -exec sed -i '/<div class="row">/,/<\/div>.*<\/div>/d' {} \;
python3 wikiarchivedir/watermarkPDF.py

# Convert to PDF

# Set PATH and DISPLAY variables to help cron find commands and handle GUI-related tools
export PATH="/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin:/usr/local/sbin"
export DISPLAY=:0

# Define directories and file paths
input_dir="wikiarchivehtmlurl"
input_dir_images="wikiarchivehtmlurl/images"
combined_html_dir="wikiarchivehtmlcombined"
combined_html_dir_images="wikiarchivehtmlcombined/images"
combined_html_file="wikiarchivehtmlcombined/archivedname.html"
output_pdf_dir="wikiarchivepdf"
cover_page="coverpagedir"

# Generate timestamp for the output file
timestamp=$(date +"%Y%m%d%H%M%S")
output_pdf_file="$output_pdf_dir/${timestamp}_archivedname.pdf"

cover_html="coverpagehtml"

# Ensure the output and combined HTML directories exist
mkdir -p "$combined_html_dir"
mkdir -p "$output_pdf_dir"

# Copy images directory
echo "Copying images directory from $input_dir_images to $combined_html_dir_images"
cp -R "$input_dir_images" "$combined_html_dir_images"

# Combine all HTML files into a single HTML file with page breaks
echo "Combining HTML files into $combined_html_file"
> "$combined_html_file"
find "$input_dir" -type f -name '*.html' | sort | while IFS= read -r file; do
    cat "$file" >> "$combined_html_file"
    echo -e "\n<div style=\"page-break-after: always;\"></div>\n" >> "$combined_html_file"
done

# Adjust image paths in the combined HTML file to be relative to the new location
echo "Adjusting image paths in $combined_html_file"
sed -i -E 's|src="/images/|src="images/|g' "$combined_html_file"

# Generate the final PDF using wkhtmltopdf
echo "Generating PDF at $output_pdf_file"

wkhtmltopdf --enable-local-file-access \
    "$cover_html" \
    toc \
    "$combined_html_file" \
    --footer-left "Page [page]" \
    --footer-center "Copyright $(date +'%Y')" \
    "$output_pdf_file"

# Check if the PDF was generated successfully
if [ -f "$output_pdf_file" ]; then
    echo "PDF successfully generated at $output_pdf_file"
else
    echo "Failed to generate PDF."
    exit 1
fi

chown -R www-data:www-data wikiarchivedir