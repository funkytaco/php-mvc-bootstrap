# Save Template Documentation

The Template Manager's save functionality allows users to create new templates or update existing ones through the `saveTemplate()` method. This documentation covers how the save feature works and what to expect when using it.

## Overview

The save template functionality handles both creating new templates and updating existing ones automatically. It collects all necessary template data, including content, variables, and metadata before sending it to the server.

## Implementation Details

### Data Collection

The save process collects the following data:
- Template content from the CodeMirror editor
- Template name (defaults to 'New Template')
- Template type (defaults to 'page')
- All defined variables including:
  - Variable names
  - Default values
  - Variable types (string, number, or boolean)

### Request Structure

The save endpoint expects a POST request with the following JSON structure:

```javascript
{
  "template": {
    "content": "Template content here",
    "name": "Template name",
    "type": "page",
    "variables": [
      {
        "name": "variableName",
        "default_value": "defaultValue",
        "type": "string"
      }
    ]
  }
}
```

### Endpoints

- New templates: POST to `/templates/new`
- Existing templates: POST to `/templates/{id}`

### Error Handling

The save process includes comprehensive error handling:
- Network errors are caught and reported
- Server-side errors are displayed to the user
- Validation errors are shown in the UI
- All errors are logged to the console for debugging

## User Feedback

The system provides immediate feedback through notifications:
- Success: "Template saved successfully"
- Error: Specific error message from the server or a generic error message

## URL Management

For new templates, after a successful save:
- The URL is automatically updated with the new template ID
- Browser history is updated using pushState
- The template ID is stored in the TemplateManager instance

## Technical Considerations

1. Content-Type header is set to `application/json`
2. Empty variable names are filtered out before saving
3. Variables are validated and cleaned before submission
4. The save process is asynchronous and non-blocking

## Example Usage

```javascript
// Save the current template
await templateManager.saveTemplate();

// The promise resolves when the save is complete
// Any errors will be caught and displayed to the user
```

## Security Notes

- All template data is sanitized before saving
- Variable names are validated to prevent injection
- Server-side validation is expected for all saved data

## Browser Support

The save functionality uses modern JavaScript features:
- Async/await
- Fetch API
- URL API
- JSON parsing/stringifying

Ensure your browser environment supports these features or include appropriate polyfills.