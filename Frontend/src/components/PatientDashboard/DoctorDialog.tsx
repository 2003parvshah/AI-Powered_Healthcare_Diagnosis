import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import { Button } from "../ui/button";
import { Briefcase, Check, ChevronRight, DollarSign, Star } from "lucide-react";
// import DoctorAvailableTime from "./DoctorAvailableTime";
import { Separator } from "../ui/separator";
import { useState } from "react";
import { motion } from "framer-motion";
import { useSelector } from "react-redux";
import { RootState } from "@/redux/store";
import axios from "axios";
import DoctorAvailableTimee from "./DoctorAvailableTimee";
const doctor = {
  name: "Dr. John Doe",
  specialty: "Cardiology",
};

export const DoctorDialog = ({ id }: { id: number }) => {
  const token = useSelector((state: RootState) => state.auth.token);
  const [confirmed, setConfirmed] = useState(false);
  const [selectedTimeSlot, setSelectedTimeSlot] = useState<string | null>(null);
  const [open, setOpen] = useState(false);
  const handleTimeSlotSelection = (selectedSlot: string) => {
    console.log(selectedTimeSlot);

    setSelectedTimeSlot(selectedSlot);
  };
  const handleBookAppointment = async () => {
    if (!selectedTimeSlot) {
      alert("Please select an appointment time.");
      return;
    }

    const appointmentData = {
      doctor_id: id,
      health_issues_id: 1, // Replace with actual selected health issue ID
      appointment_date: selectedTimeSlot,
    };

    try {
      const response = await axios.post(
        `${import.meta.env.VITE_BASE_URL}/patient/setAppointment`,
        appointmentData,
        {
          headers: { Authorization: `Bearer ${token}` },
        },
      );
      console.log(response);

      if (response.status === 201) {
        alert("Appointment booked successfully!");
        setOpen(false);
      } else {
        alert("Failed to book the appointment. Please try again.");
      }
    } catch (error) {
      console.error("Error booking appointment:", error);
      alert("An error occurred. Please try again later.");
    }
  };

  // useEffect(() => {
  const fetchDoctorDetails = async () => {
    try {
      const response = await axios.post(
        `${import.meta.env.VITE_BASE_URL}/patient/getdoctors_timetable`,
        { doctor_id: id },
        {
          headers: { Authorization: `Bearer ${token}` },
        },
      );
      console.log(response);
    } catch (error) {
      console.log(error);
    }
  };
  // fetchDoctorDetails();
  // }, [doctor_id, token]);
  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogTrigger asChild>
        <Button
          size="lg"
          className="rounded font-normal"
          onClick={fetchDoctorDetails}
        >
          Book Appointment <ChevronRight strokeWidth={3} />
        </Button>
      </DialogTrigger>
      <DialogContent className="bg-secondary flex h-11/12 w-11/12 max-w-5xl flex-col justify-between overflow-y-scroll md:h-11/12 md:flex-row lg:w-4xl">
        <DialogHeader className="flex shrink-0 flex-col items-center justify-center gap-4 p-4">
          {/* <div className=""> */}
          <img
            src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
            alt={doctor.name}
            className="aspect-square w-24 rounded-full object-cover"
          />
          <div className="flex flex-col items-center justify-evenly">
            <DialogTitle className="text-2xl font-medium">
              {doctor.name}
            </DialogTitle>
            <DialogDescription className="text-muted-foreground flex max-w-64 flex-col text-center">
              <span>{doctor.specialty}</span>
              <span>MBBS, BCS, MCPS (Gynae & Obs), MCRCG</span>
            </DialogDescription>
          </div>
          <div className="flex flex-col gap-2">
            <div className="bg-background flex w-full justify-between gap-4 rounded p-3 shadow">
              <div className="flex items-center justify-center gap-2">
                <div className="bg-secondary rounded-full p-2">
                  <Briefcase />
                </div>
                <div>
                  <p className="text-muted-foreground text-sm">
                    Total Experience
                  </p>
                  <p className="text-lg font-semibold">12 years</p>
                </div>
              </div>
              <Separator orientation="vertical" />
              <div className="flex items-center">
                <div className="bg-secondary rounded-full p-2">
                  <Star fill="black" size={18} />
                </div>
                <div>
                  <p className="text-muted-foreground text-sm">Rating</p>
                  <p className="text-lg font-semibold">4.8(500)</p>
                </div>
              </div>
            </div>
            <div className="bg-background flex items-center justify-start gap-2 rounded p-3 shadow">
              <div className="bg-secondary aspect-square rounded-full p-2">
                <DollarSign />
              </div>

              <div className="">
                <p className="text-lg font-semibold">$200</p>
                <p className="text-muted-foreground">Consultation</p>
              </div>
            </div>
          </div>
          {/* </div> */}
        </DialogHeader>
        <div className="flex flex-col items-stretch gap-4">
          <div className="flex flex-col gap-2">
            <p className="text-muted-foreground">Select Date</p>
            {/* <DoctorAvailableTime doctor_id={id} /> */}
            <DoctorAvailableTimee
              id={id}
              onTimeSlotSelect={handleTimeSlotSelection}
            />
            <div>
              <p className="text-muted-foreground">Select Issue</p>
              <Button variant="outline">Temp</Button>
            </div>
            <div className="flex gap-1 overflow-hidden">
              <motion.div
                className="w-full"
                layout
                transition={{ duration: 0.2, ease: "easeOut" }}
              >
                <Button
                  size="lg"
                  className="w-full"
                  onClick={() => setConfirmed(!confirmed)}
                >
                  Book Appointment
                </Button>
              </motion.div>

              {confirmed && (
                <motion.div
                  initial={{ x: 50 }}
                  animate={{ x: 0 }}
                  transition={{ duration: 0.3, ease: "easeOut" }}
                >
                  <Button size="lg" onClick={() => handleBookAppointment()}>
                    <Check />
                  </Button>
                </motion.div>
              )}
            </div>
          </div>
        </div>
      </DialogContent>
    </Dialog>
  );
};
