# Dashboard

## Content

### Header
- Title 'Admin Dashboard' for 'admin' users
- Title 'Dashboard' for non-admin users

### Appointments Card
- Pull upcoming appointments (last week)
- Admins: display patients name, doctor name, and date
- Practitioners: display patients name and date
- Patients: display doctor name and date

### Doctor Card
- Only display for 'patient' users
- Pull practitioners with user information and match name with `Config.user.info.doctor_name`

### Contact Info Card
- Display user information from `Config.user.info`
- Name, email, phone, location

### Support Card
- Display support information from Config
- Name, email, phone, availability

## Components to Build
- Header [NEW] (can take heading, filters, and/or action)
- Row (refactor with Tachyons)
- Column (refactor with Tachyons)
- Card [NEW] (takes heading and slot for children)
- CardContent [NEW] (gives padding and bottom border for card content)
- Heading2 [NEW] (for Card names)
- Heading3 [NEW] (for Card headings)
- Heading4 [NEW] (for Card content labels)
