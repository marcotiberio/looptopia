# BlockWysiwygAccordion

A flexible component that allows you to create up to 4 columns of WYSIWYG content with customizable widths using a 12-column grid system.

## Features

- **Up to 4 columns**: Add between 1-4 columns of content
- **12-column grid system**: Each column can be 1/12 to 12/12 wide
- **Responsive design**: Separate width controls for desktop and mobile
- **WYSIWYG editor**: Full content editing capabilities for each column
- **Optional buttons**: Add call-to-action buttons to each column
- **Customizable spacing**: Choose from different gap sizes between columns
- **Vertical alignment**: Control how columns align vertically
- **Background colors**: Set custom background colors for the entire block

## Usage

1. Add the component to your page
2. Configure the number of columns (1-4)
3. For each column:
   - Set the column width (1/12 to 12/12)
   - Set the mobile column width (12/12, 6/12, 4/12, or 3/12)
   - Add your WYSIWYG content
   - Optionally add a button
4. Configure global options:
   - Column gap spacing
   - Vertical alignment
   - Background color

## Column Width Examples

- **Two equal columns**: 6/12 + 6/12
- **Three equal columns**: 4/12 + 4/12 + 4/12
- **Sidebar layout**: 8/12 + 4/12
- **Feature layout**: 3/12 + 6/12 + 3/12

## Technical Details

- Uses CSS Grid with 12 columns
- Responsive breakpoints for mobile optimization
- Compatible with existing Flynt theme styling
- Maintains accessibility standards 
