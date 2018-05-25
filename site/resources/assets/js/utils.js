export const Utils = {
    /**
     * Converts interval time to days/hours/minutes
     */
    updateEventTimer: function(event) {
        let days;
        let hours;
        let totalTime = event.command_timer;

        event.command_interval_days = 0;
        event.command_interval_hours = 0;
        event.command_interval_minutes = 5;

        if (totalTime) {
            days = totalTime / 1440;

            if (days >= 1) {
                days = Math.floor(days);
                event.command_interval_days = days;
                totalTime -= (days * 1440);
            }

            hours = totalTime / 60;

            if (hours >= 1) {
                hours = Math.floor(hours);
                event.command_interval_hours = hours;
                totalTime -= (hours * 60);
            }

            event.command_interval_minutes = totalTime;
        }
    }
}
