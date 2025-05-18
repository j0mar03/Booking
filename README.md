# Classroom Booking System

A web-based classroom booking system for managing classroom reservations and schedules.

## Features

- Classroom booking management
- User authentication and authorization
- Schedule management
- Admin dashboard
- Automated deployment to InfinityFree

## Requirements

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- Git

## Installation

1. Clone the repository:
```bash
git clone https://github.com/YOUR_USERNAME/classroom-booking.git
cd classroom-booking
```

2. Install dependencies:
```bash
composer install
```

3. Copy environment file:
```bash
cp .env.example .env
```

4. Configure your environment variables in `.env`

5. Run the application:
```bash
php -S localhost:8000
```

## Development

1. Create a new branch:
```bash
git checkout -b feature/your-feature-name
```

2. Make your changes

3. Run tests:
```bash
composer test
```

4. Push your changes:
```bash
git add .
git commit -m "Your commit message"
git push origin feature/your-feature-name
```

5. Create a Pull Request on GitHub

## Deployment

The system uses GitHub Actions for continuous integration and deployment. When code is pushed to the main branch:

1. Tests are run automatically
2. Code quality checks are performed
3. If all checks pass, the code is deployed to InfinityFree

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is licensed under the MIT License.

# classroombookings - open source room booking system for schools.

By Craig A Rodway.

[![License: AGPLv3](https://img.shields.io/static/v1?label=License&message=AGPLv3&color=3DA639&style=flat-square)](https://www.gnu.org/licenses/agpl-3.0.html)
[![Twitter Follow](https://img.shields.io/twitter/follow/crbsapp.svg?style=social)](https://twitter.com/crbsapp)

This is a web-based room booking system for schools and is designed to be as easy to use as possible. Set up your bookable rooms, day schedule and timetable for the year. Add user accounts, and allow them to make and manage bookings from anywhere.

It is available to [download and install yourself](https://www.classroombookings.com/download/) or there is a great value [hosted service](https://www.classroombookings.com/pricing/).

It is web-based - PHP and MySQL - and currently uses the [CodeIgniter 3](https://codeigniter.com/) framework.

## Documentation
For installation instructions and configuration guide, please [read the documentation pages](https://www.classroombookings.com/documentation/).

## Bug Reports & Feature Requests
Please check out [GitHub Issues](https://github.com/craigrodway/classroombookings/issues) to view existing issues or open a new bug report.

## Security
To report any security issues, please email craig@classroombookings.com instead of using the issue tracker.

## Credits

This project makes use of several third parties, some of which are listed below.

- [CodeIgniter](https://codeigniter.com/) (MIT)
- [Unpoly](https://unpoly.com/) (MIT)
- [FamFamFam Silk Icons](http://www.famfamfam.com/lab/icons/silk/) ([CC BY 3.0](https://creativecommons.org/licenses/by/3.0/), Unmodified)
