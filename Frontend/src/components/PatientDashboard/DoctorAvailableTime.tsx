import { useEffect, useState } from "react";
import { ChevronLeft, ChevronRight } from "lucide-react";
import { cn } from "@/lib/utils";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import axios from "axios";
import { useSelector } from "react-redux";
import { RootState } from "@/redux/store";

interface TimeSlot {
  time: string;
  available: boolean;
}

export default function DoctorAvailableTime({
  doctor_id,
}: {
  doctor_id: number;
}) {
  const [timeSlots, setTimeSlots] = useState<TimeSlot[]>([]);
  const token = useSelector((state: RootState) => state.auth.token);
  const [month, setMonth] = useState(new Date().getMonth()); // 0 - 11
  const [year, setYear] = useState(new Date().getFullYear());
  const [selectedDate, setSelectedDate] = useState<number | null>(null);
  const [calendarDays, setCalendarDays] = useState<
    Array<{ date: number; day: string; isCurrentMonth: boolean } | null>
  >([]);

  // Day names for the calendar header
  const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

  // Generate calendar days for the current month view
  useEffect(() => {
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDayOfMonth = new Date(year, month, 1).getDay();

    // Create array for the calendar grid
    const days = [];

    // Add empty cells for days before the first of the month
    for (let i = 0; i < firstDayOfMonth; i++) {
      days.push(null);
    }

    // Add days of the current month
    for (let i = 1; i <= daysInMonth; i++) {
      const date = new Date(year, month, i);
      days.push({
        date: i,
        day: date.toLocaleDateString("en-US", { weekday: "short" }),
        isCurrentMonth: true,
      });
    }

    setCalendarDays(days);

    // If no date is selected, select today if it's in the current month view
    if (selectedDate === null) {
      const today = new Date();
      if (today.getMonth() === month && today.getFullYear() === year) {
        setSelectedDate(today.getDate());
      } else {
        setSelectedDate(1);
      }
    }
  }, [month, year, selectedDate]);

  // Fetch time slots when the selected date changes
  useEffect(() => {
    if (selectedDate === null) return;

    const fetchSchedule = async () => {
      try {
        const formattedDate = `${year}-${String(month + 1).padStart(2, "0")}-${String(selectedDate).padStart(2, "0")}`;
        const response = await axios.post(
          `${import.meta.env.VITE_BASE_URL}/patient/getdoctors_timetable`,
          { doctor_id: doctor_id, date: formattedDate },
          { headers: { Authorization: `Bearer ${token}` } },
        );

        if (response.status === 200) {
          const schedule = response.data.schedule;
          const formattedSlots = schedule.map(
            (slot: { start_time: string; available: boolean }) => ({
              time: new Date(slot.start_time + "Z").toLocaleTimeString(
                "en-US",
                {
                  hour: "2-digit",
                  minute: "2-digit",
                  hour12: true,
                },
              ),
              available: slot.available,
            }),
          );
          setTimeSlots(formattedSlots);
        }
      } catch (error) {
        console.error("Error fetching schedule:", error);
        // Fallback with dummy data similar to the reference image
        // setTimeSlots([
        //   { time: "06:00 PM", available: false },
        //   { time: "06:30 PM", available: false },
        //   { time: "07:00 PM", available: true },
        //   { time: "07:30 PM", available: false },
        //   { time: "08:00 PM", available: true },
        //   { time: "08:30 PM", available: false },
        //   { time: "09:00 PM", available: false },
        //   { time: "10:00 PM", available: true },
        // ]);
      }
    };

    fetchSchedule();
  }, [selectedDate, month, year, token, doctor_id]);

  const getMonthName = () => {
    return new Date(year, month).toLocaleDateString("en-US", { month: "long" });
  };

  return (
    <Card className="mx-auto w-full rounded-lg border-0 shadow-sm">
      <CardContent className="p-6">
        {/* Month and Navigation */}
        <div className="mb-6 flex items-center justify-between">
          <h2 className="text-2xl font-semibold">{getMonthName()}</h2>
          <div className="flex gap-2">
            <Button
              variant="outline"
              size="icon"
              className="h-10 w-10 rounded-full"
              onClick={() => {
                if (month === 0) {
                  setMonth(11);
                  setYear(year - 1);
                } else {
                  setMonth(month - 1);
                }
              }}
            >
              <ChevronLeft className="h-5 w-5" />
              <span className="sr-only">Previous month</span>
            </Button>
            <Button
              variant="outline"
              size="icon"
              className="h-10 w-10 rounded-full"
              onClick={() => {
                if (month === 11) {
                  setMonth(0);
                  setYear(year + 1);
                } else {
                  setMonth(month + 1);
                }
              }}
            >
              <ChevronRight className="h-5 w-5" />
              <span className="sr-only">Next month</span>
            </Button>
          </div>
        </div>

        {/* Calendar - Day Names */}
        <div className="mb-2 grid grid-cols-7 gap-1">
          {dayNames.map((day) => (
            <div key={day} className="text-center font-medium text-gray-500">
              {day}
            </div>
          ))}
        </div>

        {/* Calendar - Date Grid */}
        <div className="mb-8 grid grid-cols-7 gap-1">
          {calendarDays.map((day, index) =>
            day ? (
              <Button
                key={index}
                variant={selectedDate === day.date ? "default" : "ghost"}
                className={cn(
                  "flex h-12 w-full items-center justify-center rounded-md",
                  selectedDate === day.date
                    ? "bg-blue-500 text-white"
                    : "hover:bg-gray-100",
                )}
                onClick={() => setSelectedDate(day.date)}
              >
                {day.date}
              </Button>
            ) : (
              <div key={index} className="h-12 w-full"></div>
            ),
          )}
        </div>

        {/* Available Time Slots */}
        <div className="grid grid-cols-4 gap-3">
          {timeSlots.map((slot, index) => (
            <Button
              key={index}
              variant={slot.available ? "outline" : "ghost"}
              className={cn(
                "h-12 rounded-md border",
                slot.available
                  ? "border-gray-200 text-gray-900 hover:border-blue-500"
                  : "cursor-not-allowed text-gray-300",
              )}
              disabled={!slot.available}
            >
              {slot.time}
            </Button>
          ))}
        </div>

        {/* View All Availability */}
        {/* <div className="mt-6">
          <Button
            variant="ghost"
            className="w-full justify-between font-medium text-gray-700"
            asChild
          >
            <a href="#">
              View all availability
              <ChevronRight className="h-5 w-5" />
            </a>
          </Button>
        </div> */}
      </CardContent>
    </Card>
  );
}
