#!/bin/bash

# Remove all <a> tags
find wikiarchivehtml -name "*.html" -exec perl -i -pe 's|<a\b[^>]*>(.*?)</a>|$1|g' {} +

# Replace email addresses with a span styled with a black background
find wikiarchivehtml -name "*.html" -exec perl -i -pe 's|[\w\.\-]+@[\w\.\-]+\.[a-zA-Z]{2,7}|<span style="background-color: #000; color: #fff;">&nbsp;&nbsp;&nbsp;REDACTED&nbsp;</span>|g' {} +
