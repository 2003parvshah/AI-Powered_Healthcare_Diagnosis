import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Badge } from "@/components/ui/badge";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { Calendar, Mail, Phone, User } from "lucide-react";

export const Profile = () => {
  return (
    <section className="flex max-w-4xl flex-col items-start justify-start space-y-6 p-4">
      {/* Header Section */}
      <div className="mb-6 flex items-center gap-4">
        <Avatar className="h-16 w-16">
          <AvatarImage
            className="object-cover"
            src="https://images.unsplash.com/photo-1619895862022-09114b41f16f?q=80&w=570&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
            alt="Jerome"
          />
          <AvatarFallback>JB</AvatarFallback>
        </Avatar>
        <div>
          <div className="flex items-center gap-2">
            <h1 className="text-2xl font-bold">Jerome Bellingham</h1>
            <Badge variant="outline" className="bg-green-100 text-emerald-700">
              PATIENT
            </Badge>
          </div>
          <p className="text-muted-foreground text-sm">
            Joined Since : 12 March 2023
          </p>
        </div>
      </div>

      <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
        {/* Basic Information */}
        <Card>
          <CardHeader>
            <CardTitle>Basic Informational</CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="flex items-center gap-3">
              <User className="text-muted-foreground h-5 w-5" />
              <div>
                <p className="text-muted-foreground text-sm">Gender</p>
                <p>Male</p>
              </div>
            </div>
            <div className="flex items-center gap-3">
              <Calendar className="text-muted-foreground h-5 w-5" />
              <div>
                <p className="text-muted-foreground text-sm">Birthday</p>
                <p>12 August 2001</p>
              </div>
            </div>
            <div className="flex items-center gap-3">
              <Phone className="text-muted-foreground h-5 w-5" />
              <div>
                <p className="text-muted-foreground text-sm">Phone Number</p>
                <p>+62 837 356 343 23</p>
              </div>
            </div>
            <div className="flex items-center gap-3">
              <Mail className="text-muted-foreground h-5 w-5" />
              <div>
                <p className="text-muted-foreground text-sm">Email</p>
                <p>jeromebellingham93@mail.com</p>
              </div>
            </div>
          </CardContent>
        </Card>

        {/* Appointment Schedule */}
        <Card>
          <CardHeader>
            <CardTitle>Appointment Schedule</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="">
              {[
                {
                  date: "12 Oct 2023",
                  title: "Prosthetic Tooth Fabrication",
                  doctor: "Drs. Wade Warren",
                },
                {
                  date: "12 Sep 2023",
                  title: "Post-Surgical Care",
                  doctor: "Drs Marvin McKinney",
                },
                {
                  date: "12 Aug 2023",
                  title: "Implant Placement",
                  doctor: "Drs Floyd Miles",
                },
              ].map((appointment, index) => (
                <div
                  key={index}
                  className="relative border-l-2 border-blue-200 pb-4 pl-6"
                >
                  <div className="absolute left-0 h-4 w-4 -translate-x-1/2 rounded-full bg-blue-500" />
                  <div>
                    <p className="text-sm font-medium text-blue-500">
                      {appointment.date}
                    </p>
                    <p className="font-medium">{appointment.title}</p>
                    <p className="text-muted-foreground text-sm">
                      {appointment.doctor}
                    </p>
                    <p> </p>
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
        {/* History Dental */}
        <Card className="w-full sm:col-span-2">
          <CardHeader>
            <CardTitle>Past Appointments</CardTitle>
          </CardHeader>
          <CardContent>
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>ID</TableHead>
                  <TableHead>Type Treatment</TableHead>
                  <TableHead>Date</TableHead>
                  <TableHead>Doctor</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody className="overflow-auto">
                {[
                  {
                    id: "#12324",
                    type: "🦷 Implant",
                    date: "12 Jun 2023",
                    doctor: "Dr. John Doe",
                  },
                  {
                    id: "#20324",
                    type: "🦷 Route canal",
                    date: "4 May 2023",
                    doctor: "Dr. John Doe",
                  },
                  {
                    id: "#57686",
                    type: "🦷 Dentures",
                    date: "2 Mar 2023",
                    doctor: "Dr. John Doe",
                  },
                  {
                    id: "#68767",
                    type: "🦷 Whitening",
                    date: "16 Feb 2023",
                    doctor: "Dr. John Doe",
                  },
                ].map((item) => (
                  <TableRow key={item.id}>
                    <TableCell>{item.id}</TableCell>
                    <TableCell>{item.type}</TableCell>
                    <TableCell>{item.date}</TableCell>
                    <TableCell>
                      <span className="flex items-center gap-1">
                        {item.doctor}
                      </span>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </CardContent>
        </Card>
      </div>
    </section>
  );
};
