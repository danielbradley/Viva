CREATE VIEW view_menus AS
    SELECT menu_id, menu_name, position, page_name, timestamp, page_heading, page_url
    FROM pages LEFT JOIN menus USING (MENU_ID) ORDER BY menu_name, position, page_name, timestamp DESC;

CREATE VIEW view_pages AS
    SELECT PAGE_ID, timestamp, publish, page_template, page_name, page_heading, page_title, page_url, page_keywords, page_description, MENU_ID, position
    FROM pages ORDER BY page_name, timestamp DESC;
