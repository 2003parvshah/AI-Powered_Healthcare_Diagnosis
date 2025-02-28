import { Button } from "@/components/ui/button";
// import { Input } from "../ui/input";
import { useState } from "react";
import { motion } from "framer-motion";
import { Check } from "lucide-react";
import { DateTimePicker } from "../ui/date-time-picker";

export const SetHoliday = () => {
  const data = {
    holidays: [
      {
        id: 1,
        doctor_id: 9,
        start_date: "2025-03-01 09:00:00",
        end_date: "2025-03-02 18:00:00",
        timezone: "UTC",
        created_at: "2025-02-28T05:11:58.000000Z",
        updated_at: "2025-02-28T05:11:58.000000Z",
      },
    ],
  };
  const [confirmed, setConfirmed] = useState(false);
  return (
    <div className="mx-auto mt-6 flex flex-col gap-6 rounded-xl border bg-white p-6 shadow-sm">
      <h2 className="text-xl font-semibold">Set Holiday</h2>
      {/* <Button onClick={saveSchedule}>Save</Button> */}
      <div className="flex items-end gap-3">
        <div>
          <p>Start Date</p>
          {/* <Input type="datetime-local" /> */}
          <DateTimePicker />
        </div>
        <div>
          <p>End Date</p>
          <DateTimePicker />
          {/* <Input type="datetime-local" /> */}
        </div>
        {/* <div>
          <Button>Save</Button>
        </div> */}
        <div className="flex w-36 gap-1 overflow-hidden">
          <motion.div
            className="w-full"
            layout
            transition={{ duration: 0.2, ease: "easeOut" }}
          >
            <Button className="w-full" onClick={() => setConfirmed(!confirmed)}>
              Save
            </Button>
          </motion.div>

          {confirmed && (
            <motion.div
              initial={{ x: 50 }}
              animate={{ x: 0 }}
              transition={{ duration: 0.3, ease: "easeOut" }}
            >
              <Button>
                <Check />
              </Button>
            </motion.div>
          )}
        </div>
      </div>
      <div>
        <p className="text-xl font-semibold">Previous Holidays</p>
        {data.holidays.map((holiday) => (
          <div key={holiday.id} className="mt-2 flex gap-4">
            <div>
              <p className="text-muted-foreground">Start Date</p>
              <p>{holiday.start_date}</p>
            </div>
            <div>
              <p className="text-muted-foreground">End Date</p>
              <p>{holiday.end_date}</p>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};
