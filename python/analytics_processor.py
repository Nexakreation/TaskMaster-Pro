from flask import Flask, jsonify, request
import pandas as pd
from datetime import datetime
import json

app = Flask(__name__)

@app.route('/api/analytics/process', methods=['POST'])
def process_analytics():
    data = request.get_json()
    
    # Convert tasks data to DataFrame
    df = pd.DataFrame(data['tasks'])
    
    # Calculate monthly statistics
    monthly_data = {
        'days_in_month': pd.Period(data['year'], freq='M').days_in_month,
        'total_tasks': len(df),
        'status_distribution': {
            'completed': len(df[df['completed'] == True]),
            'pending': len(df[(df['completed'] == False) & (df['date'] >= datetime.now().strftime('%Y-%m-%d'))]),
            'overdue': len(df[(df['completed'] == False) & (df['date'] < datetime.now().strftime('%Y-%m-%d'))]),
            'today': len(df[df['date'] == datetime.now().strftime('%Y-%m-%d')])
        }
    }
    
    # Calculate daily completion stats
    daily_completion = df.groupby('date').size().reindex(
        pd.date_range(start=f"{data['year']}-{data['month']}-01", 
                     periods=monthly_data['days_in_month']),
        fill_value=0
    ).tolist()
    
    monthly_data['daily_completion'] = daily_completion
    
    return jsonify({
        'monthly_data': monthly_data,
        'success': True
    })

if __name__ == '__main__':
    app.run(port=5000) 