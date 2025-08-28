# Design System

This document outlines the visual and interaction standards for the project. It provides color palettes, typography, and component guidelines to ensure consistency across the application.

## Color Palette

The project uses a small set of brand colors exposed as CSS variables. Use these variables in stylesheets instead of hard-coded values.

```css
:root {
  --color-primary: #0055A4;
  --color-primary-light: #4da3ff;
  --color-secondary: #6C757D;
  --color-secondary-light: #ADB5BD;
  --color-accent: #FFC107;
}
```

| Role | Variable | Hex |
|------|----------|-----|
| Primary | `--color-primary` | `#0055A4` |
| Primary Light | `--color-primary-light` | `#4DA3FF` |
| Secondary | `--color-secondary` | `#6C757D` |
| Secondary Light | `--color-secondary-light` | `#ADB5BD` |
| Accent | `--color-accent` | `#FFC107` |

## Typography

All typography is based on a 16px root size and uses a simple scale with 8px increments for spacing.

| Element | Font Family | Size | Weight | Line Height |
|---------|-------------|------|--------|-------------|
| Heading 1 | "Helvetica Neue", Arial, sans-serif | 32px | 600 | 1.25 |
| Heading 2 | "Helvetica Neue", Arial, sans-serif | 24px | 600 | 1.25 |
| Body | "Roboto", "Open Sans", sans-serif | 16px | 400 | 1.5 |
| Small Text | "Roboto", "Open Sans", sans-serif | 14px | 400 | 1.5 |

Spacing between sections should use multiples of `8px` (e.g., 8, 16, 24).

## Components & Layout

### Buttons

Buttons use the primary or secondary colors for their background.

```html
<button class="btn btn-primary">Primary Action</button>
<button class="btn btn-secondary">Secondary Action</button>
```

### Grid Layout

Layouts are organized on a 12-column responsive grid:

```
+----+----+----+----+----+----+----+----+----+----+----+----+
| 1  | 2  | 3  | 4  | 5  | 6  | 7  | 8  | 9  |10  |11  |12  |
+----+----+----+----+----+----+----+----+----+----+----+----+
```

Use `gap` values based on the 8px spacing scale.

### Cards

Cards group related information and use a soft shadow with 16px padding:

```
.card {
  background: #ffffff;
  border-radius: 4px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  padding: 16px;
}
```

## Usage Example

The snippet below demonstrates combining the color and typography rules:

```html
<h1 style="color: var(--color-primary); font-family: 'Helvetica Neue', Arial, sans-serif;">Dashboard</h1>
<p style="font-family: 'Roboto', 'Open Sans', sans-serif; font-size: 16px;">Welcome back!</p>
```

Following these guidelines helps maintain a cohesive user experience throughout the application.
