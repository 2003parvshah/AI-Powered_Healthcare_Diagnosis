import { ChevronLeft, ChevronRight } from "lucide-react";

import { cn } from "@/lib/utils";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";

interface TimeSlot {
  time: string;
  available: boolean;
}

const timeSlots: TimeSlot[] = [
  { time: "06:00 PM", available: false },
  { time: "06:30 PM", available: false },
  { time: "07:00 PM", available: true },
  { time: "07:30 PM", available: false },
  { time: "08:00 PM", available: true },
  { time: "08:30 PM", available: false },
  { time: "09:00 PM", available: false },
  { time: "10:00 PM", available: true },
];

export default function DoctorAvailableTime() {
  //   const [date, setDate] = React.useState<Date>(new Date())

  return (
    <Card className="mx-auto w-full py-2">
      <CardContent className="grid gap-4">
        <div className="flex items-center justify-between">
          <h2 className="text-xl font-semibold">February</h2>
          <div className="flex gap-1">
            <Button variant="outline" size="icon" className="h-8 w-8">
              <ChevronLeft className="h-4 w-4" />
              <span className="sr-only">Previous month</span>
            </Button>
            <Button variant="outline" size="icon" className="h-8 w-8">
              <ChevronRight className="h-4 w-4" />
              <span className="sr-only">Next month</span>
            </Button>
          </div>
        </div>

        <div className="space-y-4">
          <div className="grid grid-cols-7 text-center text-sm">
            {["Fri", "Sat", "Sun", "Mon", "Tue", "Wed", "Thu"].map((day) => (
              <div key={day} className="py-2">
                {day}
              </div>
            ))}
            {[12, 13, 14, 15, 16, 17, 18].map((day) => (
              <Button
                key={day}
                variant={day === 17 ? "default" : "ghost"}
                className={cn(
                  "h-10 w-full rounded",
                  day === 17 && "bg-primary text-primary-foreground",
                )}
              >
                {day}
              </Button>
            ))}
          </div>

          <div className="grid grid-cols-2 gap-2 sm:grid-cols-4">
            {timeSlots.map((slot, index) => (
              <Button
                key={index}
                variant={slot.available ? "outline" : "ghost"}
                className={cn(
                  "h-12",
                  !slot.available && "text-muted-foreground",
                  slot.available && "hover:border-primary hover:text-primary",
                )}
                disabled={!slot.available}
              >
                {slot.time}
              </Button>
            ))}
          </div>

          <Button variant="ghost" className="w-full justify-between" asChild>
            <a href="#">
              View all availability
              <ChevronRight className="h-4 w-4" />
            </a>
          </Button>
        </div>
      </CardContent>
    </Card>
  );
}
