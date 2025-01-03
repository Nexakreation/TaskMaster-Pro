import './bootstrap';
import Alpine from 'alpinejs';
import { processTasks } from './task-operations';

window.Alpine = Alpine;
Alpine.start();

// Make processTasks available globally
window.processTasks = processTasks;
