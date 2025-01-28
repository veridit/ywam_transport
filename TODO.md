# Pending Trips Chart Migration Tasks

When comparing functionality from `old/www/html/pending_trips_chart.php` to
`transport/templates/transport/pending_trips_chart.html` and `transport/views.py`.

# AI! Solve the Time Display items.
## Time Display
- [x] Implement AM/PM time format display (4AM-10PM) instead of 24h
- [x] Add time slot column headers with AM/PM format

## Vehicle Filtering
- [ ] Add exclusion of vehicles with permanent service reservations
- [ ] Update vehicle queryset in views.py

## Debug System
- [ ] Implement debug logging system
- [ ] Add debug levels similar to PHP version
- [ ] Add debug output for AJAX/HTMX calls

## Popup/Modal System
- [ ] Add hover tooltips showing reservation numbers
- [ ] Implement click handlers for reservation details
- [ ] Add service reservation messages ("Vehicle being Repaired...")
- [ ] Style modal popup to match original

## Permissions
- [ ] Add group-based permission checks (TM/TC groups)
- [ ] Implement different interaction levels based on group
- [ ] Add permission decorators to views

## Date Navigation
- [ ] Add optional date restriction (no dates before today)
- [ ] Implement date validation in form submission
- [ ] Add date format validation

## Visual Styling
- [ ] Implement color scheme:
  - Reserved: #FF6633
  - Available: #FFF
  - Pulled: #000000
- [ ] Add color legend at bottom of chart
- [ ] Add visual indicators for different reservation types

## Session Handling
- [ ] Implement proper session expiry handling
- [ ] Add session timeout redirects
- [ ] Add session refresh mechanism

## Data Processing
- [ ] Implement hour-by-hour slot calculation
- [ ] Add time slot collision detection
- [ ] Optimize reservation queries

## Layout
- [ ] Add specific sizing for:
  - Vehicle column width
  - Day column width
  - Time slot width
  - Chart row height
- [ ] Implement precise layout control

## Error Handling
- [ ] Add comprehensive error message system
- [ ] Implement user-friendly error displays
- [ ] Add error logging

## HTMX Integration
- [ ] Complete HTMX replacement for all jQuery functionality
- [ ] Add dynamic reservation detail loading
- [ ] Implement smooth updates for navigation

## Testing
- [ ] Add unit tests for all new functionality
- [ ] Add integration tests for HTMX interactions
- [ ] Add permission-based test cases
