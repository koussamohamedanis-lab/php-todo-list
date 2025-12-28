# php-todo-list

Simple PHP + HTML To‑Do List with habit tracking and a lightweight API.

# To-Do List & Daily Habits Tracker 🎯

A complete web application for managing daily tasks and tracking habits with real-time analytics, streak tracking, and interactive dashboards.

## Quick Start ⚡

1. **Start XAMPP**: Apache + MySQL must be running (green in XAMPP Control Panel)
2. **Create Database**: Go to phpMyAdmin and create database called `testdb`
3. **Initialize App**: Visit `http://localhost/To-Do%20List/setup-habits.php`
4. **Register Account**: Go to `http://localhost/To-Do%20List/auth.php` and create account
5. **Start Using**: Login and begin managing tasks and habits!

## Features ✨

- **Task Management**: Create, edit, delete, and track tasks with due dates and times
- **Daily Habits Tracking**: Monitor daily habits with a calendar view
- **Streaks & Analytics**: Track your current streaks and completion rates  
- **Dashboard**: View statistics, charts, and habit overview with real-time data
- **User Authentication**: Secure login and registration system with bcrypt password hashing
- **Profile Management**: Update user information and preferences
- **Completion History**: View all completed tasks with filtering and sorting
- **Real-time Charts**: Interactive charts for habit completion and daily statistics

## System Requirements 📋

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache web server (XAMPP recommended)
- Modern web browser (Chrome, Firefox, Safari, Edge)

## Installation & Setup 🚀

### Step 1: Prepare Your Environment

1. **Download/Copy the project** to your XAMPP htdocs folder:
   - On Windows: `C:\xampp\htdocs\To-Do List\`
   - On Mac: `/Applications/XAMPP/xamppfiles/htdocs/To-Do List/`
   - On Linux: `/opt/lampp/htdocs/To-Do List/`

### Step 2: Configure Database Connection

Edit the `Data.php` file and ensure these settings match your MySQL configuration:

```php
private $host = 'localhost';      // Usually 'localhost'
private $dbname = 'testdb';       // Your database name
private $username = 'root';       // Your MySQL username
private $password = '';           // Your MySQL password
```

### Step 3: Create Database

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Create a new database called `testdb` (or your chosen name)
3. No need to import tables manually - they'll be created automatically

### Step 4: Initialize Tables

1. Start XAMPP (Apache and MySQL)
2. Navigate to: `http://localhost/To-Do%20List/setup-habits.php`
3. You should see a success message confirming all tables are created

If you see errors, check:
- Database credentials in `Data.php`
- MySQL service is running
- Database exists and you have permissions

## How to Use the Application 📖

### Getting Started - First Login

**Step 1: Create Your Account**
1. Go to: `http://localhost/To-Do%20List/auth.php`
2. Click "Create Account"
3. Enter username, email, and password
4. Click "Create Account"
5. You'll be redirected to login - use your credentials

**Step 2: Main Dashboard**
After login, you'll see the main task management page with:
- Navigation menu at the top (To-Do List, Habits, Dashboard, History, Profile)
- Input field to add new tasks
- Your task list displayed below

### How to Use: Tasks/To-Do List

**Add a New Task:**
1. Go to main page (or click "To-Do List" in navigation)
2. Fill in the form:
   - **Task Name**: What you need to do
   - **Date**: When it's due (picker opens on click)
   - **Time**: What time it's due (optional)
   - **Color**: Choose a color for visual organization
3. Click "Add" button
4. Task appears in your list below

**Complete a Task:**
1. Find the task in your list
2. Click the checkbox next to task name
3. Task moves to completed section
4. Check "History" page to see all completed tasks

**Edit a Task:**
1. Find the task in your list
2. Click the task name or "Edit" button
3. Update any field (name, date, time, color)
4. Click "Update" or "Save"

**Delete a Task:**
1. Find the task in your list
2. Click the "Delete" or trash icon
3. Confirm deletion
4. Task is permanently removed

**Task Colors:**
Each task can have a different color for visual organization:
- Red: Urgent/High Priority
- Blue: Work-related
- Green: Personal
- Yellow: Shopping/Errands
- Purple: Health/Fitness
- Or choose any color you prefer!

### How to Use: Habits Tracking

**What Are Habits?**
Unlike one-time tasks, habits are things you want to do regularly (daily, weekly, etc.). Examples:
- Exercise for 30 minutes
- Drink 8 glasses of water
- Read for 30 minutes
- Meditate
- Practice coding

**Create a New Habit:**
1. Click "Habits" in the navigation menu
2. Click "Create Habit" or "+ New Habit"
3. Fill in the form:
   - **Habit Name**: What you want to track
   - **Color**: Visual color for the habit
   - **Description**: Optional notes about the habit
   - **Category**: Optional (e.g., Health, Learning, etc.)
   - **Frequency**: How often (Daily, Weekly, etc.)
4. Click "Create" button
5. Habit appears in your habit list

**Complete a Habit Today:**
1. Go to "Habits" page
2. Find your habit in the list
3. Click "Complete Today" or the checkbox for today
4. Habit is marked as completed for that day
5. Your streak counter increases!

**View Habit Calendar:**
Each habit shows:
- **This Month Calendar**: Visual grid showing completed days (marked in color)
- **Completion Bar**: Percentage of days you completed this habit this month
- **Current Streak**: 🔥 How many days in a row you've done the habit
- **Statistics**: Total habits, days tracked, average completion %

**Track Multiple Habits:**
- You can create as many habits as you want
- Each habit is tracked separately
- Complete multiple habits daily to build momentum
- Compare completion percentages between habits

**Delete a Habit:**
1. Go to "Habits" page
2. Find the habit
3. Click "Delete" or trash icon
4. Click "Confirm" to permanently delete
5. ⚠️ This also deletes all tracking history for that habit

### How to Use: Dashboard 📊

**What is the Dashboard?**
Your analytics hub showing:
- Overall statistics and progress
- Habit performance charts
- Completion trends
- Daily progress visualization

**Dashboard Statistics:**
Shows 4 key metrics:
1. **Total Habits**: How many habits you're tracking
2. **Days Tracked**: How many days you've logged habit completions
3. **Avg Completion Rate**: Average percentage of habits completed daily
4. **Tasks Completed**: How many one-time tasks you've finished

**Dashboard Charts:**
1. **Daily Completion Chart**: Line graph showing last 7 days of habit completion
   - X-axis: Days of week
   - Y-axis: Completion percentage
   - Helps you see trends

2. **Habit Distribution Pie Chart**: Shows which habits contribute most
   - Each color represents a different habit
   - Helps identify your most consistent habits

**Using Dashboard:**
1. Click "Dashboard" in navigation
2. Page auto-loads your statistics
3. Charts update automatically
4. Auto-refreshes every 30 seconds
5. Click on habit in list for more details

### How to Use: History 📜

**What is History?**
A complete log of all your completed tasks.

**View Your Task History:**
1. Click "History" in navigation
2. See list of all completed tasks
3. Most recent completions appear first
4. Each task shows:
   - Task name
   - Original due date
   - Color category
   - Completion date/time

**Filter/Search History:**
1. Use browser's Find function (Ctrl+F)
2. Search for task names
3. Or scroll through your history

**Delete from History:**
1. Find task in history
2. Click "Delete" if available
3. Removes from completed task list

### How to Use: Profile 👤

**What is Profile?**
Your account settings and information page.

**View Your Profile:**
1. Click "Profile" in navigation
2. See your username and email
3. View account creation information

**Update Your Email:**
1. Click "Profile"
2. Find your current email
3. Click "Edit" next to email
4. Enter new email address
5. Click "Save" or "Update"
6. New email is saved to account

**Logout:**
1. Click "Logout" button (usually in top right)
2. Or click "Profile" and find logout option
3. You're logged out and returned to login page
4. Your data is saved and available next time you login

### Tips & Best Practices 💡

**For Task Management:**
- ✅ Use different colors for different task categories
- ✅ Set realistic due dates for better planning
- ✅ Complete overdue tasks first each day
- ✅ Review completed tasks for motivation

**For Habit Tracking:**
- ✅ Start with just 2-3 habits to build consistency
- ✅ Choose habits that matter to you personally
- ✅ Check habits off daily for the best tracking
- ✅ Watch your streaks grow over time
- ✅ Don't worry if you miss a day - just restart!

**For Dashboard Usage:**
- ✅ Check dashboard daily to see progress
- ✅ Aim to increase your completion percentage each week
- ✅ Compare current week to last week
- ✅ Celebrate improvement!

**Best Practices:**
- ✅ Log in daily to track habits consistently
- ✅ Break large tasks into smaller ones
- ✅ Be specific with habit names (not just "Exercise", but "30 min run")
- ✅ Review history monthly to see progress
- ✅ Adjust habits if they're too easy or too hard

### Step 5: Start Using the App!

1. Go to: `http://localhost/To-Do%20List/auth.php`
2. Register a new account
3. Login with your credentials
4. Start managing tasks and habits!

## Application Structure 🏗️

```
├── index.html          - Main task list view
├── habits.html         - Daily habits calendar view
├── dashboard.html      - Analytics and statistics (NEW!)
├── history.html        - Completed tasks view
├── profile.html        - User profile management
├── auth.php            - Login/Registration page
├── api.php             - API endpoints for all operations
├── Data.php            - Database connection and managers
├── setup-habits.php    - Database table setup
├── CREATE_TABLES.sql   - SQL schema for manual setup
└── README.md           - This file
```

## Key Features Explained 📖

### 1. **Task Management** (index.html)
- Add tasks with name, date, time, and color
- Mark tasks as completed/incomplete
- Edit existing tasks
- Delete tasks
- Auto-expiration detection for overdue tasks
- Persistent storage in database

### 2. **Daily Habits** (habits.html)
- Calendar view showing all days of the month
- Check/uncheck daily habit completion
- Add new habits with custom colors
- Delete habits with all their tracking data
- Real-time completion percentage visualization
- Monthly stats including total habits, days tracked, and average completion

### 3. **Dashboard** (dashboard.html) **NEW!**
- Overview statistics (total habits, days tracked, completion rate)
- Daily completion line chart (last 7 days)
- Habit distribution pie chart
- Individual habit streaks and completion percentages
- Auto-refresh every 30 seconds

### 4. **History** (history.html)
- View all completed tasks
- Sorted by most recent first
- Display original task information (date, time, color)
- Quick delete option if needed

### 5. **Profile** (profile.html)
- View current username and email
- Edit email address
- Secure account management
- Session-based authentication

## API Endpoints 🔗

All endpoints require authentication via session. Base URL: `api.php`

### Tasks
- `GET api.php?action=getTasks` - Get all user tasks
- `POST api.php?action=addTask` - Create new task
- `POST api.php?action=updateTask` - Update task details
- `GET api.php?action=deleteTask&id=X` - Delete task
- `GET api.php?action=completeTask&id=X&done=1` - Toggle task completion
- `GET api.php?action=getHistory` - Get completed tasks

### Habits
- `GET api.php?action=getHabits` - Get all habits
- `POST api.php?action=addHabit` - Create new habit
- `POST api.php?action=checkHabit` - Toggle daily completion
- `GET api.php?action=deleteHabit&id=X` - Delete habit
- `GET api.php?action=getHabitStats&id=X&days=30` - Get habit statistics
- `GET api.php?action=getHabitDetails&id=X` - Get habit with streak info
- `GET api.php?action=getUserStats` - Get overall user statistics
- `POST api.php?action=updateHabit` - Update habit details

### User
- `GET api.php?action=getProfile` - Get current user info
- `POST api.php?action=updateProfile` - Update user email
- `GET api.php?action=logout` - Logout user

## Database Schema 🗄️

### users
```sql
id              INT PRIMARY KEY
username        VARCHAR(255) UNIQUE
email           VARCHAR(255) UNIQUE
password        VARCHAR(255) (hashed)
created_date    TIMESTAMP
```

### tasks
```sql
id              INT PRIMARY KEY
user_id         INT FOREIGN KEY
name            VARCHAR(255)
date            DATE
time            TIME
color           VARCHAR(7)
done            BOOLEAN
created_at      TIMESTAMP
```

### habits
```sql
id              INT PRIMARY KEY
user_id         INT FOREIGN KEY
name            VARCHAR(255)
color           VARCHAR(7)
description     VARCHAR(500)
category        VARCHAR(50)
frequency       VARCHAR(20)
created_date    TIMESTAMP
```

### habit_tracking
```sql
id              INT PRIMARY KEY
habit_id        INT FOREIGN KEY
tracking_date   DATE
completed       BOOLEAN
notes           VARCHAR(500)
created_at      TIMESTAMP
```

### achievements
```sql
id              INT PRIMARY KEY
habit_id        INT FOREIGN KEY
achievement_type VARCHAR(50)
value           INT
achieved_date   TIMESTAMP
```

## Troubleshooting 🔧

### "Database connection failed"
- Check `Data.php` has correct credentials
- Verify MySQL is running in XAMPP
- Ensure database exists

### "Tables don't exist"
- Run `http://localhost/To-Do%20List/setup-habits.php` again
- Check error messages for permission issues
- Verify database user has CREATE TABLE permissions

### "Can't login"
- Check username and password are correct
- Clear browser cookies/cache
- Verify `users` table exists in database

### "Tasks/Habits not showing"
- Check browser console (F12) for errors
- Verify API responses in Network tab
- Check user_id is correctly stored in session

### "Habit checkboxes not working"
- Check browser console for JavaScript errors
- Verify API endpoint is returning success
- Check database user_id matches session user_id

## Browser Compatibility 🌐

- Chrome/Chromium 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Security Features 🔒

- Password hashing with bcrypt
- Session-based authentication
- SQL injection prevention via prepared statements
- CSRF protection through session validation
- Input validation and sanitization
- Automatic logout on invalid session

## Performance Tips ⚡

1. **Clear Browser Cache**: If styles don't update correctly
2. **Refresh Database**: Delete old test data if app runs slow
3. **Check Server Logs**: Look at `xampp/apache/logs/error.log` for issues
4. **Monitor Browser Console**: Check for JavaScript errors with F12

## Future Enhancements 🚀

- [ ] Email notifications for habit reminders
- [ ] Habit sharing and group tracking
- [ ] Mobile app version
- [ ] Advanced analytics and reports
- [ ] Habit templates library
- [ ] Integration with calendar applications
- [ ] Export data as PDF/CSV
- [ ] Dark mode toggle
- [ ] Multiple themes

## Credits & License 📝

Created as a comprehensive task and habit tracking solution.

**Last Updated**: December 2025
**Version**: 2.0

## Support & Feedback 💬

If you encounter any issues:
1. Check the Troubleshooting section above
2. Verify all files are in the correct location
3. Ensure all dependencies are installed
4. Check browser console for error messages

---

**Happy tracking! 🎯✨**
