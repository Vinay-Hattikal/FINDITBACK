# FindItBack

FindItBack is a belongings finder platform designed to help individuals locate lost items on the college campus and allow users to claim items they have found. This user-friendly website ensures transparency and efficiency in managing lost-and-found items through a well-organized dashboard, notification system, and claim verification process.

## Features

### User Features
- **User Registration & Login**: Secure user authentication for accessing personalized dashboards.
- **Upload Found Items**: Users can provide details and images of items they have found.
- **Search for Lost Items**: Users can browse through uploaded items to find their lost belongings.
- **Claim Items**: Submit claims for items with proof of ownership.
- **Notifications**: Real-time Gmail updates for claim requests.

### Admin Features
- **Verification**: Review and verify claims submitted by students.

## Tech Stack
- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Gmail Notifications**: PHPMailer

## Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/finditback.git
   ```

2. Navigate to the project directory:
   ```bash
   cd finditback
   ```

3. Set up the database:
   - Import the `finditback.sql` file into your MySQL server.
   - Configure the database connection in the backend PHP files (e.g., `config.php`).

4. Start the local server:
   - Use tools like XAMPP or WAMP to host the project locally.
   - Place the project folder in the `htdocs` directory.

5. Access the application:
   - Open your browser and navigate to `http://localhost/finditback`.

## Usage
1. Register as a new user and log in.
2. Upload details of found items or search for your lost belongings.
3. Claim items by providing valid proof of ownership.
4. Receive real-time Gmail notifications about claim requests.

