#include <emscripten.h>
#include <stdlib.h>
#include <string.h>

// Task structure matching JavaScript object
typedef struct {
    int id;
    char text[256];
    char date[11];  // YYYY-MM-DD + null terminator
    int completed;
} Task;

// Filter status constants
#define FILTER_ALL 0
#define FILTER_COMPLETED 1
#define FILTER_INCOMPLETE 2
#define FILTER_OVERDUE 3
#define FILTER_PENDING 4

// Sort criteria constants
#define SORT_DATE_DESC 0
#define SORT_DATE_ASC 1
#define SORT_NAME_ASC 2
#define SORT_NAME_DESC 3

// Helper function to compare dates
int compare_dates(const char* date1, const char* date2) {
    return strcmp(date1, date2);
}

// Helper function to check if task is overdue
int is_overdue(Task* task, const char* current_date) {
    return !task->completed && compare_dates(task->date, current_date) < 0;
}

// Helper function for sorting
int compare_tasks(const void* a, const void* b, int sort_criteria) {
    Task* task1 = (Task*)a;
    Task* task2 = (Task*)b;

    switch (sort_criteria) {
        case SORT_DATE_DESC:
            return -compare_dates(task1->date, task2->date);
        case SORT_DATE_ASC:
            return compare_dates(task1->date, task2->date);
        case SORT_NAME_ASC:
            return strcmp(task1->text, task2->text);
        case SORT_NAME_DESC:
            return -strcmp(task1->text, task2->text);
        default:
            return 0;
    }
}

// Quicksort implementation
void quick_sort(Task* tasks, int left, int right, int sort_criteria) {
    if (left >= right) return;

    Task pivot = tasks[right];
    int i = left - 1;

    for (int j = left; j < right; j++) {
        if (compare_tasks(&tasks[j], &pivot, sort_criteria) <= 0) {
            i++;
            Task temp = tasks[i];
            tasks[i] = tasks[j];
            tasks[j] = temp;
        }
    }

    Task temp = tasks[i + 1];
    tasks[i + 1] = tasks[right];
    tasks[right] = temp;

    quick_sort(tasks, left, i, sort_criteria);
    quick_sort(tasks, i + 2, right, sort_criteria);
}

// Main processing function
EMSCRIPTEN_KEEPALIVE
int process_tasks(Task* input_tasks, int task_count, Task* output_tasks, 
                 int filter_status, int sort_criteria, const char* current_date) {
    int output_count = 0;

    // Filter tasks
    for (int i = 0; i < task_count; i++) {
        int include_task = 0;

        switch (filter_status) {
            case FILTER_ALL:
                include_task = 1;
                break;
            case FILTER_COMPLETED:
                include_task = input_tasks[i].completed;
                break;
            case FILTER_INCOMPLETE:
                include_task = !input_tasks[i].completed;
                break;
            case FILTER_OVERDUE:
                include_task = is_overdue(&input_tasks[i], current_date);
                break;
            case FILTER_PENDING:
                include_task = !input_tasks[i].completed && !is_overdue(&input_tasks[i], current_date);
                break;
        }

        if (include_task) {
            output_tasks[output_count++] = input_tasks[i];
        }
    }

    // Sort filtered tasks
    if (output_count > 0) {
        quick_sort(output_tasks, 0, output_count - 1, sort_criteria);
    }

    return output_count;
}

// Memory management functions
EMSCRIPTEN_KEEPALIVE
Task* create_task_array(int size) {
    return (Task*)malloc(size * sizeof(Task));
}

EMSCRIPTEN_KEEPALIVE
void free_task_array(Task* array) {
    free(array);
}

// Helper function to set task data
EMSCRIPTEN_KEEPALIVE
void set_task_data(Task* task, int id, const char* text, const char* date, int completed) {
    task->id = id;
    strncpy(task->text, text, 255);
    task->text[255] = '\0';
    strncpy(task->date, date, 10);
    task->date[10] = '\0';
    task->completed = completed;
} 