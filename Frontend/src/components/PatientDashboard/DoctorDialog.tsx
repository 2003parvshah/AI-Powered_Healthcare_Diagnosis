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
import DoctorAvailableTime from "./DoctorAvailableTime";
import { Separator } from "../ui/separator";
import { useState } from "react";
import { motion } from "framer-motion";
const doctor = {
  name: "Dr. John Doe",
  specialty: "Cardiology",
};

export const DoctorDialog = () => {
  const [confirmed, setConfirmed] = useState(false);

  return (
    <Dialog>
      <DialogTrigger asChild>
        <Button size="lg" className="rounded font-normal">
          Book Appointment <ChevronRight strokeWidth={3} />
        </Button>
      </DialogTrigger>
      <DialogContent className="bg-secondary flex h-11/12 w-11/12 max-w-5xl flex-col justify-between overflow-y-auto md:h-auto md:flex-row lg:w-3xl">
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
            <DialogDescription className="text-muted-foreground max-w-64 text-center">
              <p>{doctor.specialty}</p>
              <p>MBBS, BCS, MCPS (Gynae & Obs), MCRCG</p>
            </DialogDescription>
          </div>
          {/* </div> */}
        </DialogHeader>
        <div className="flex flex-col items-stretch gap-4">
          <div className="flex flex-col gap-2">
            <div className="bg-background flex w-full justify-between rounded p-3 shadow">
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
          <div className="flex flex-col gap-2">
            <p className="text-muted-foreground">Available Time</p>
            <DoctorAvailableTime />
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
                  <Button size="lg">
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
