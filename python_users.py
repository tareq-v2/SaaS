import csv
import random
import pandas as pd
from datetime import datetime, timedelta
import uuid

def generate_random_users(num_users=480):
    """Generate random user data"""

    # Sample data for realistic generation
    first_names = [
        'James', 'Mary', 'John', 'Patricia', 'Robert', 'Jennifer', 'Michael', 'Linda',
        'David', 'Elizabeth', 'William', 'Barbara', 'Richard', 'Susan', 'Joseph', 'Jessica',
        'Thomas', 'Sarah', 'Christopher', 'Karen', 'Charles', 'Helen', 'Daniel', 'Nancy',
        'Matthew', 'Betty', 'Anthony', 'Dorothy', 'Mark', 'Lisa', 'Donald', 'Sandra',
        'Steven', 'Donna', 'Paul', 'Carol', 'Andrew', 'Ruth', 'Joshua', 'Sharon',
        'Kenneth', 'Michelle', 'Kevin', 'Laura', 'Brian', 'Sarah', 'George', 'Kimberly'
    ]

    last_names = [
        'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis',
        'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson',
        'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin', 'Lee', 'Perez', 'Thompson',
        'White', 'Harris', 'Sanchez', 'Clark', 'Ramirez', 'Lewis', 'Robinson', 'Walker',
        'Young', 'Allen', 'King', 'Wright', 'Scott', 'Torres', 'Nguyen', 'Hill', 'Flores'
    ]

    domains = [
        'gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'aol.com',
        'icloud.com', 'protonmail.com', 'mail.com', 'yandex.com', 'zoho.com'
    ]

    cities = [
        'New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia',
        'San Antonio', 'San Diego', 'Dallas', 'San Jose', 'Austin', 'Jacksonville',
        'Fort Worth', 'Columbus', 'Charlotte', 'San Francisco', 'Indianapolis',
        'Seattle', 'Denver', 'Washington', 'Boston', 'El Paso', 'Nashville',
        'Detroit', 'Oklahoma City', 'Portland', 'Las Vegas', 'Memphis', 'Louisville',
        'Baltimore', 'Milwaukee', 'Albuquerque', 'Tucson', 'Fresno', 'Mesa',
        'Sacramento', 'Atlanta', 'Kansas City', 'Colorado Springs', 'Miami'
    ]

    states = [
        'AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA', 'HI', 'ID', 'IL',
        'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT',
        'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI',
        'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'
    ]

    job_titles = [
        'Software Engineer', 'Marketing Manager', 'Sales Representative', 'Data Analyst',
        'Project Manager', 'Graphic Designer', 'Accountant', 'HR Specialist',
        'Customer Service Representative', 'Business Analyst', 'Product Manager',
        'Operations Manager', 'Financial Advisor', 'Web Developer', 'Content Writer',
        'Social Media Manager', 'Quality Assurance Tester', 'Network Administrator',
        'Database Administrator', 'UX/UI Designer', 'Teacher', 'Nurse', 'Doctor',
        'Lawyer', 'Engineer', 'Consultant', 'Architect', 'Chef', 'Photographer'
    ]

    users = []

    print(f"Generating {num_users} random users...")

    for i in range(num_users):
        if (i + 1) % 500 == 0:
            print(f"Generated {i + 1} users...")

        first_name = random.choice(first_names)
        last_name = random.choice(last_names)

        # Generate email
        email_prefix = f"{first_name.lower()}.{last_name.lower()}"
        if random.random() < 0.3:  # 30% chance to add numbers
            email_prefix += str(random.randint(1, 999))
        email = f"{email_prefix}@{random.choice(domains)}"

        # Generate age (18-80)
        age = random.randint(18, 80)

        # Generate birth date based on age
        today = datetime.now()
        birth_year = today.year - age
        birth_date = datetime(
            birth_year,
            random.randint(1, 12),
            random.randint(1, 28)  # Simplified to avoid date issues
        )

        # Generate phone number
        phone = f"({random.randint(200, 999)}) {random.randint(200, 999)}-{random.randint(1000, 9999)}"

        # Generate address
        street_number = random.randint(1, 9999)
        street_names = ['Main St', 'Oak Ave', 'Park Dr', 'Elm St', 'Cedar Ln', 'Pine Rd',
                       'Maple Ave', 'Washington St', 'Lincoln Ave', 'Jefferson Dr']
        address = f"{street_number} {random.choice(street_names)}"
        city = random.choice(cities)
        state = random.choice(states)
        zip_code = f"{random.randint(10000, 99999)}"

        # Generate salary ($30k - $150k)
        salary = random.randint(30000, 150000)

        # Generate join date (within last 5 years)
        start_date = today - timedelta(days=random.randint(1, 1825))

        user = {
            'user_id': str(uuid.uuid4()),
            'first_name': first_name,
            'last_name': last_name,
            'full_name': f"{first_name} {last_name}",
            'email': email,
            'phone': phone,
            'age': age,
            'birth_date': birth_date.strftime('%Y-%m-%d'),
            'address': address,
            'city': city,
            'state': state,
            'zip_code': zip_code,
            'job_title': random.choice(job_titles),
            'salary': salary,
            'hire_date': start_date.strftime('%Y-%m-%d'),
            'is_active': random.choice([True, False]) if random.random() < 0.1 else True,  # 90% active
            'created_at': datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        }

        users.append(user)

    return users

def export_to_csv(users, filename='random_users.csv'):
    """Export users to CSV format"""
    print(f"Exporting to CSV: {filename}")

    if not users:
        print("No users to export!")
        return

    with open(filename, 'w', newline='', encoding='utf-8') as csvfile:
        fieldnames = users[0].keys()
        writer = csv.DictWriter(csvfile, fieldnames=fieldnames)

        writer.writeheader()
        for user in users:
            writer.writerow(user)

    print(f"Successfully exported {len(users)} users to {filename}")

def export_to_laravel_csv(users, filename='laravel_users.csv'):
    """Export users to Laravel-compatible CSV format"""
    print(f"Exporting Laravel-compatible CSV: {filename}")

    if not users:
        print("No users to export!")
        return

    with open(filename, 'w', newline='', encoding='utf-8') as csvfile:
        # Laravel controller expects: Name,Email,Phone,Address
        fieldnames = ['Name', 'Email', 'Phone', 'Address']
        writer = csv.DictWriter(csvfile, fieldnames=fieldnames)

        writer.writeheader()
        for user in users:
            # Map our user data to Laravel format
            laravel_user = {
                'Name': user['full_name'],
                'Email': user['email'],
                'Phone': user['phone'],
                'Address': f"{user['address']}, {user['city']}, {user['state']} {user['zip_code']}"
            }
            writer.writerow(laravel_user)

    print(f"Successfully exported {len(users)} users to {filename} (Laravel format)")

def export_to_excel(users, filename='random_users.xlsx'):
    """Export users to Excel format"""
    print(f"Exporting to Excel: {filename}")

    if not users:
        print("No users to export!")
        return

    # Convert to DataFrame
    df = pd.DataFrame(users)

    # Create Excel writer with multiple sheets
    with pd.ExcelWriter(filename, engine='openpyxl') as writer:
        # Main data sheet
        df.to_excel(writer, sheet_name='Users', index=False)

        # Summary statistics sheet
        summary_data = {
            'Metric': [
                'Total Users',
                'Average Age',
                'Active Users',
                'Inactive Users',
                'Average Salary',
                'Unique Cities',
                'Unique States',
                'Most Common Job Title'
            ],
            'Value': [
                len(users),
                round(df['age'].mean(), 1),
                len(df[df['is_active'] == True]),
                len(df[df['is_active'] == False]),
                f"${df['salary'].mean():,.0f}",
                df['city'].nunique(),
                df['state'].nunique(),
                df['job_title'].mode().iloc[0] if not df['job_title'].mode().empty else 'N/A'
            ]
        }

        summary_df = pd.DataFrame(summary_data)
        summary_df.to_excel(writer, sheet_name='Summary', index=False)

        # Age distribution sheet
        age_groups = pd.cut(df['age'], bins=[17, 25, 35, 45, 55, 65, 100],
                           labels=['18-25', '26-35', '36-45', '46-55', '56-65', '65+'])
        age_dist = age_groups.value_counts().reset_index()
        age_dist.columns = ['Age Group', 'Count']
        age_dist.to_excel(writer, sheet_name='Age Distribution', index=False)

    print(f"Successfully exported {len(users)} users to {filename}")

def main():
    """Main function to generate and export users"""
    print("Random User Generator")
    print("=" * 50)

    # Generate users
    users = generate_random_users(480)

    if users:
        print(f"\nGenerated {len(users)} users successfully!")

        # Export to CSV (full format)
        export_to_csv(users, 'random_users_5000.csv')

        # Export to Laravel-compatible CSV
        export_to_laravel_csv(users, 'laravel_import_users.csv')

        # Export to Excel
        export_to_excel(users, 'random_users_5000.xlsx')

        # Display sample of generated data
        print("\nSample of generated users:")
        print("-" * 50)
        for i, user in enumerate(users[:3]):
            print(f"User {i+1}:")
            for key, value in user.items():
                print(f"  {key}: {value}")
            print()

        print("Export complete! Files created:")
        print("- random_users_5000.csv (full format)")
        print("- laravel_import_users.csv (Laravel controller compatible)")
        print("- random_users_5000.xlsx (Excel with multiple sheets)")

        print("\nLaravel Import Instructions:")
        print("1. Use 'laravel_import_users.csv' for your Laravel import")
        print("2. The CSV has columns: Name, Email, Phone, Address")
        print("3. Compatible with your UserImportController structure")
        print("4. Users will be created with default password 'password123'")

    else:
        print("Failed to generate users!")

if __name__ == "__main__":
    # Check if required libraries are available
    try:
        import pandas as pd
        main()
    except ImportError:
        print("Error: pandas library is required for Excel export.")
        print("Install it using: pip install pandas openpyxl")
        print("\nGenerating CSV only...")

        # Generate users and export to CSV only
        users = generate_random_users(5000)
        if users:
            export_to_csv(users, 'random_users_5000.csv')
            export_to_laravel_csv(users, 'laravel_import_users.csv')
            print(f"CSV export complete with {len(users)} users!")
            print("Laravel-compatible CSV: laravel_import_users.csv")
