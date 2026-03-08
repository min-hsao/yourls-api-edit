# YOURLS API Edit & Delete Plugin

Adds `update` and `delete` actions to the YOURLS API. YOURLS core currently only supports creating, expanding, and stats via API. This plugin enables full CRUD support for external applications.

## Features

- **Update API Action**: Change the destination URL and title of an existing short URL.
- **Delete API Action**: Remove an existing short URL.
- Works with standard signature-based or username/password authentication.

## Installation

1. Create a folder named `api-edit` in your `user/plugins` directory.
2. Upload `plugin.php` to that folder.
3. Activate the plugin in your YOURLS admin panel (Plugins section).

## API Usage

All actions require your YOURLS signature or username/password for authentication.

### Update a Short URL

Updates the destination URL and title for an existing keyword.

**Action:** `update`

**Parameters:**
- `shorturl`: The keyword to update (e.g., `bx402`)
- `url`: The new destination URL
- `title` (optional): The new title. If omitted, YOURLS will attempt to fetch it from the URL.

**Example Request:**
`GET https://yourls.example.com/yourls-api.php?signature=...&action=update&shorturl=bx402&url=https://new-destination.com&title=New+Title&format=json`

### Delete a Short URL

Deletes an existing short URL.

**Action:** `delete`

**Parameters:**
- `shorturl`: The keyword to delete.

**Example Request:**
`GET https://yourls.example.com/yourls-api.php?signature=...&action=delete&shorturl=bx402&format=json`

## License
MIT
