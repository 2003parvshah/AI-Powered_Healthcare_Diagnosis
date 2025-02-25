import { ChevronLeft, ChevronRight } from "lucide-react";
import {
  Calendar,
  CalendarCurrentDate,
  CalendarDayView,
  CalendarMonthView,
  CalendarNextTrigger,
  CalendarPrevTrigger,
  CalendarTodayTrigger,
  CalendarViewTrigger,
  CalendarWeekView,
} from "../Calendar";

export const AppointmentCalendar = () => {
  return (
    <Calendar
      events={[
        {
          id: "1",
          start: new Date("2025-02-25T02:30:00.000000Z"),
          end: new Date("2025-02-25T03:30:00.000000Z"),
          title: "event A",
          color: "pink",
        },
        {
          id: "2",
          start: new Date("2025-02-26T01:30:00.000000Z"),
          end: new Date("2025-02-26T02:30:27.000000Z"),
          title: "event A",
          color: "blue",
        },
      ]}
    >
      <div className="flex h-dvh flex-col py-6">
        <div className="mb-6 flex items-center gap-2 px-6">
          <CalendarViewTrigger
            className="aria-[current=true]:bg-accent"
            view="day"
          >
            Day
          </CalendarViewTrigger>
          <CalendarViewTrigger
            view="week"
            className="aria-[current=true]:bg-accent"
          >
            Week
          </CalendarViewTrigger>
          <CalendarViewTrigger
            view="month"
            className="aria-[current=true]:bg-accent"
          >
            Month
          </CalendarViewTrigger>

          <span className="flex-1" />

          <CalendarCurrentDate />

          <CalendarPrevTrigger>
            <ChevronLeft size={20} />
            <span className="sr-only">Previous</span>
          </CalendarPrevTrigger>

          <CalendarTodayTrigger>Today</CalendarTodayTrigger>

          <CalendarNextTrigger>
            <ChevronRight size={20} />
            <span className="sr-only">Next</span>
          </CalendarNextTrigger>

          {/* <ModeToggle /> */}
        </div>

        <div className="relative flex-1 overflow-auto px-6">
          <CalendarDayView />
          <CalendarWeekView />
          <CalendarMonthView />
        </div>
      </div>
    </Calendar>
  );
};
