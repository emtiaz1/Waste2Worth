
# 🌱 Waste2Worth

A community-driven waste management platform that transforms environmental responsibility into rewarding experiences. Users can report waste locations, collect reported waste, and earn Eco Coins for their contributions to creating a cleaner environment.

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

## 🌟 Features

### 🎯 Core Features
- **Waste Reporting**: Users can report waste locations with photos, GPS coordinates, and detailed descriptions
- **Collection System**: Community members can claim and collect reported waste
- **Eco Coins Rewards**: Earn virtual currency for environmental contributions
- **Real-time Dashboard**: Live activity feed and community statistics
- **Leaderboard**: Community rankings based on environmental impact
- **User Profiles**: Comprehensive profile management with impact tracking

### 📊 Dashboard Features
- Personal impact metrics (waste reported/collected, eco coins earned)
- Available waste collections in your area
- My collection assignments and status tracking
- Recent community activity feed with real-time updates
- Global impact statistics
- Community events and challenges

### 🏆 Gamification
- **Eco Coins System**: Earn coins for reporting and collecting waste
- **Achievement Badges**: Unlock badges for milestones (Eco Warrior, Top Contributor)
- **Community Ranking**: Compete with other users for environmental impact
- **Progress Tracking**: Monitor weekly and monthly goals

## 🚀 Installation

### Prerequisites
- PHP 8.1+
- Composer
- Node.js & npm
- MySQL/PostgreSQL
- Laravel 10.x

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/waste2worth.git
   cd waste2worth
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your `.env` file**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=waste2worth
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   
   # File storage configuration
   FILESYSTEM_DISK=public
   
   # Mail configuration (for notifications)
   MAIL_MAILER=smtp
   MAIL_HOST=your_smtp_host
   MAIL_PORT=587
   MAIL_USERNAME=your_email
   MAIL_PASSWORD=your_password
   ```

6. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Create storage link**
   ```bash
   php artisan storage:link
   ```

8. **Build assets**
   ```bash
   npm run build
   # or for development
   npm run dev
   ```

9. **Start the application**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` to access the application.

## 🏗️ Project Structure

```
waste2worth/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   └── Providers/           # Service providers
├── database/
│   ├── factories/           # Model factories
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── public/
│   ├── css/                # Stylesheets
│   │   ├── home.css        # Dashboard styles
│   │   ├── dashboard.css   # Dashboard components
│   │   ├── appbar.css      # Navigation styles
│   │   ├── reward.css      # Rewards system styles
│   │   └── welcome.css     # Landing page styles
│   └── js/                 # JavaScript files
│       ├── appbar.js       # Navigation functionality
│       └── leaderboard.js  # Leaderboard interactions
├── resources/
│   └── views/              # Blade templates
│       ├── home.blade.php  # Main dashboard
│       └── layouts/        # Layout templates
└── routes/
    └── web.php             # Web routes
```

## 🎮 Usage

### For Regular Users

1. **Register/Login**: Create an account or sign in
2. **Report Waste**: Use the "Report Waste" button to submit new waste locations
3. **Collect Waste**: Browse available collections and claim them
4. **Earn Rewards**: Complete collections to earn Eco Coins
5. **Track Progress**: Monitor your environmental impact on the dashboard

### For Collectors

1. **Browse Available Collections**: View waste reports available for collection
2. **Request Collection**: Click "Collect" on available waste reports
3. **Submit Collection**: Once collected, submit proof with photos and actual weight
4. **Earn Eco Coins**: Receive rewards based on waste collected

### Admin Features

- Verify submitted collections
- Manage user accounts and reports
- Monitor community statistics
- Organize environmental events

## 🛠️ Technologies Used

- **Backend**: Laravel 10.x (PHP)
- **Frontend**: Bootstrap 5.3, Vanilla JavaScript
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Breeze/Sanctum
- **File Storage**: Laravel Storage
- **Real-time Updates**: AJAX polling
- **Icons**: Font Awesome
- **Styling**: Custom CSS with Bootstrap

## 📱 Features in Detail

### Dashboard Components

- **User Overview Card**: Profile information, status, and quick actions
- **Statistics Cards**: Eco coins, waste reports, community ranking
- **Available Collections**: Browse and claim waste collection opportunities
- **My Collection Tasks**: Track assigned collections and submit completions
- **My Waste Reports**: Monitor status of reported waste
- **Community Activity Feed**: Real-time updates of community actions
- **Global Impact Metrics**: Platform-wide environmental impact statistics

### Responsive Design

The application is fully responsive and optimized for:
- Desktop browsers
- Tablet devices
- Mobile phones

## 🤝 Contributing

We welcome contributions to make Waste2Worth even better! Here's how you can help:

1. **Fork the repository**
2. **Create a feature branch** (`git checkout -b feature/amazing-feature`)
3. **Commit your changes** (`git commit -m 'Add some amazing feature'`)
4. **Push to the branch** (`git push origin feature/amazing-feature`)
5. **Open a Pull Request**

### Contribution Guidelines

- Follow PSR-12 coding standards
- Write clear commit messages
- Add tests for new features
- Update documentation as needed
- Ensure responsive design compatibility

## 🐛 Issues

Found a bug or have a feature request? Please open an issue on our [GitHub Issues](https://github.com/yourusername/waste2worth/issues) page.


## 🌍 Environmental Impact

Waste2Worth is more than just an application - it's a movement towards sustainable waste management and community engagement. Every report, every collection, and every user action contributes to a cleaner, healthier environment.

### Our Mission
- Reduce environmental waste through community action
- Gamify environmental responsibility
- Create awareness about waste management
- Build stronger, more environmentally conscious communities

## 🙏 Acknowledgments

- Laravel community for the amazing framework
- Bootstrap team for the UI components
- Font Awesome for the iconography
- All contributors and community members making our environment better

---

**Made with ❤️ for a cleaner planet**

*Together, we're making a difference! Every report counts towards a cleaner environment.*
