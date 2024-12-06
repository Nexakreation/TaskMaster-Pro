import sys
import json
import pandas as pd
from datetime import datetime, timedelta

def analyze_tasks(tasks_json):
    # Convert JSON string to Python object
    tasks = json.loads(tasks_json)
    
    # If no tasks, return empty data
    if not tasks:
        return json.dumps({
            'monthly_data': {
                'days_in_month': 0,
                'total_tasks': 0,
                'status_distribution': {
                    'completed': 0,
                    'pending': 0,
                    'overdue': 0,
                    'today': 0
                },
                'daily_completion': []
            }
        })
    
    # Convert to DataFrame for easier analysis
    df = pd.DataFrame(tasks)
    
    # Convert date strings to datetime
    df['date'] = pd.to_datetime(df['date'])
    
    # Get month info from the first task's date
    first_task_date = df['date'].iloc[0]
    current_month = first_task_date.month
    current_year = first_task_date.year
    
    # Check if it's current month
    today = datetime.now()
    is_current_month = (current_month == today.month and current_year == today.year)
    
    # Calculate efficiency score without completed_at
    efficiency_score = round((len(df[df['completed']]) / len(df) * 100) if len(df) > 0 else 0)
    
    # Basic monthly statistics
    monthly_data = {
        'days_in_month': pd.Period(f"{current_year}-{current_month}").days_in_month,
        'total_tasks': len(df),
        'is_current_month': is_current_month,
        'status_distribution': {
            'completed': int(df['completed'].sum()),
            'pending': int(len(df[(~df['completed']) & (df['date'] >= datetime.now())])) if is_current_month else None,
            'overdue': int(len(df[(~df['completed']) & (df['date'] < datetime.now())])),
            'today': int(len(df[df['date'].dt.date == datetime.now().date()])) if is_current_month else None
        }
    }
    
    # Daily completion analysis
    daily_completion = df.groupby(df['date'].dt.day).size().reindex(
        range(1, monthly_data['days_in_month'] + 1), 
        fill_value=0
    ).tolist()
    monthly_data['daily_completion'] = daily_completion
    
    # Analyze weekly patterns
    df['day_of_week'] = df['date'].dt.day_name()
    weekly_pattern = df.groupby('day_of_week').size().to_dict()
    most_productive_day = max(weekly_pattern.items(), key=lambda x: x[1])[0] if weekly_pattern else 'No Data'
    
    # Calculate improvement metrics
    recent_tasks = df[df['date'] >= (datetime.now() - timedelta(days=7))]
    recent_completion_rate = round((len(recent_tasks[recent_tasks['completed']]) / len(recent_tasks) * 100)
                                 if len(recent_tasks) > 0 else 0)
    
    # Prepare final analytics data
    analytics_data = {
        'monthly_data': monthly_data,
        'efficiency_score': efficiency_score,
        'most_productive_day': most_productive_day,
        'weekly_pattern': weekly_pattern,
        'completion_streak': 0,  # Removed since we don't have completed_at
        'recent_completion_rate': recent_completion_rate,
        'improvement_rate': round(recent_completion_rate - efficiency_score, 1),
        'tasks_due_today': len(df[df['date'].dt.date == datetime.now().date()]),
        'tasks_due_this_week': len(df[
            (df['date'] >= datetime.now()) & 
            (df['date'] <= datetime.now() + timedelta(days=7))
        ])
    }
    
    return json.dumps(analytics_data)

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({'error': 'No task data provided'}))
        sys.exit(1)
    
    try:
        result = analyze_tasks(sys.argv[1])
        print(result)
    except Exception as e:
        print(json.dumps({'error': str(e)}))
        sys.exit(1) 