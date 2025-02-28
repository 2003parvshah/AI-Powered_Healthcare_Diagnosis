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

import { Button } from "@/components/ui/button";

import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";

import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Pencil } from "lucide-react";
import { useEffect, useState } from "react";
import axios from "axios";
import { useSelector } from "react-redux";
import { RootState } from "@/redux/store";
import { UserInterface } from "@/interface/user";
export const Profile = () => {
  const token = useSelector((state: RootState) => state.auth.token);
  const user = useSelector((state: RootState) => state.auth.user);
  const [personalInfo, setPersonalInfo] = useState<Partial<UserInterface>>({});
  // const [userData, setUserData] = useState<Partial<UserInterface>>({});
  const [isEditingProfile, setIsEditingProfile] = useState(false);
  const [editData, setEditData] = useState<Partial<UserInterface>>({});
  const [isUploading, setIsUploading] = useState(false);
  const handleUpdateProfile = async () => {
    setIsUploading(true);
    console.log(editData);

    try {
      const formData = new FormData();
      formData.append("name", editData.name || "");
      formData.append("date_of_birth", editData.date_of_birth || "");
      formData.append("gender", editData.gender || "");
      formData.append("address", editData.address || "");
      formData.append("blood_group", editData.blood_group || "");
      formData.append("weight", editData.weight || "");
      if (editData.profile_photo instanceof File) {
        formData.append("profile_photo", editData.profile_photo);
      }

      const response = await axios.post(
        `${import.meta.env.VITE_BASE_URL}/patient/setProfile`,
        formData,
        {
          headers: {
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
          },
        },
      );
      console.log(response);

      if (response.status === 200) {
        setPersonalInfo(response.data.patient);
      } else {
        console.error("Error updating profile:", response.statusText);
      }
    } catch (err) {
      console.error("Error updating profile:", err);
    } finally {
      setIsUploading(false);
      setIsEditingProfile(false);
    }
  };
  useEffect(() => {
    const fetchProfileDetails = async () => {
      try {
        const response = await axios.get(
          `${import.meta.env.VITE_BASE_URL}/patient/getProfile`,
          {
            headers: {
              Accept: "application/json",
              Authorization: `Bearer ${token}`,
            },
          },
        );
        console.log(response);

        if (response.status === 200) {
          setPersonalInfo(response.data.patient);
          setEditData(response.data.patient);
        }
      } catch (error) {
        console.log(error);
      }
    };
    fetchProfileDetails();
  }, [token]);
  useEffect(() => {
    console.log(editData);
  }, [editData]);

  return (
    <section className="flex max-w-5xl flex-col items-start justify-start space-y-6 p-4">
      {/* Header Section */}
      <div className="mb-6 flex items-center gap-4">
        <Avatar className="h-16 w-16">
          <AvatarImage
            className="object-cover"
            src={
              typeof personalInfo.profile_photo === "string"
                ? personalInfo.profile_photo
                : undefined
            }
            alt="Jerome"
          />
          <AvatarFallback>JB</AvatarFallback>
        </Avatar>
        <div>
          <div className="flex items-center gap-2">
            <h1 className="text-2xl font-bold">{personalInfo?.name}</h1>
            <Badge variant="outline" className="bg-green-100 text-emerald-700">
              PATIENT
            </Badge>
          </div>
          <p className="text-muted-foreground text-sm">
            Joined Since :{" "}
            {personalInfo?.created_at
              ? new Date(personalInfo.created_at).toDateString()
              : "N/A"}
          </p>
        </div>
      </div>

      <div className="grid w-full grid-cols-1 gap-6 md:grid-cols-2">
        {/* Basic Information */}
        <Card className="w-full">
          <CardHeader>
            <CardTitle className="flex items-center justify-between">
              Basic Informational
              <Dialog
                open={isEditingProfile}
                onOpenChange={setIsEditingProfile}
              >
                <DialogTrigger asChild>
                  <Button size="sm" variant="outline">
                    <Pencil size={15} />
                  </Button>
                </DialogTrigger>
                <DialogContent className="h-11/12 overflow-auto">
                  <DialogHeader>
                    <DialogTitle>Edit Profile</DialogTitle>
                    <DialogDescription>
                      Update your profile details and save the changes.
                    </DialogDescription>
                  </DialogHeader>
                  <div className="grid gap-4 py-4">
                    <div>
                      <Label htmlFor="name" className="text-right">
                        Name
                      </Label>
                      <Input
                        id="name"
                        value={editData?.name || ""}
                        className="col-span-3"
                        onChange={(e) =>
                          setEditData({
                            ...editData,
                            name: e.target.value,
                          })
                        }
                      />
                    </div>
                    <div>
                      <Label htmlFor="phone_number" className="text-right">
                        Phone number
                      </Label>
                      <Input
                        id="phone_number"
                        value={editData?.phone_number || ""}
                        className="col-span-3"
                        onChange={(e) =>
                          setEditData({
                            ...editData,
                            phone_number: e.target.value,
                          })
                        }
                      />
                    </div>
                    <div>
                      <Label htmlFor="dob" className="text-right">
                        Date of Birth
                      </Label>
                      <Input
                        type="text"
                        id="dob"
                        value={editData.date_of_birth || ""}
                        className="col-span-3"
                        onChange={(e) =>
                          setEditData({
                            ...editData,
                            date_of_birth: e.target.value,
                          })
                        }
                      />
                    </div>
                    <div>
                      <Label>Gender</Label>
                      <Select
                        onValueChange={(value) =>
                          setEditData({
                            ...editData,
                            gender: value,
                          })
                        }
                        defaultValue={editData.gender}
                      >
                        <SelectTrigger>
                          <SelectValue placeholder={editData.gender} />
                        </SelectTrigger>
                        <SelectContent id="gender">
                          <SelectItem value="male">Male</SelectItem>
                          <SelectItem value="female">Female</SelectItem>
                        </SelectContent>
                      </Select>
                    </div>
                    <div>
                      <Label htmlFor="address" className="text-right">
                        Address
                      </Label>
                      <Input
                        id="address"
                        value={editData.address || ""}
                        className="col-span-3"
                        onChange={(e) =>
                          setEditData({
                            ...editData,
                            address: e.target.value,
                          })
                        }
                      />
                    </div>
                    <div>
                      <Label htmlFor="blood_group" className="text-right">
                        Blood group
                      </Label>
                      <Input
                        type="text"
                        id="blood_group"
                        value={editData.blood_group || ""}
                        className="col-span-3"
                        onChange={(e) =>
                          setEditData({
                            ...editData,
                            blood_group: e.target.value,
                          })
                        }
                      />
                    </div>
                    <div>
                      <Label htmlFor="weight" className="text-right">
                        Weight
                      </Label>
                      <Input
                        type="text"
                        id="weight"
                        value={editData.weight || ""}
                        className="col-span-3"
                        onChange={(e) =>
                          setEditData({
                            ...editData,
                            weight: e.target.value,
                          })
                        }
                      />
                    </div>
                    <div>
                      <Label htmlFor="profile_photo" className="text-right">
                        Profile Photo
                      </Label>
                      <Input
                        type="file"
                        id="profile_photo"
                        // value={editData.profile_photo || ""}
                        className="col-span-3"
                        onChange={(e) =>
                          setEditData({
                            ...editData,
                            profile_photo: e.target.files
                              ? e.target.files[0]
                              : undefined,
                          })
                        }
                      />
                    </div>
                  </div>
                  <DialogFooter>
                    <Button
                      onClick={handleUpdateProfile}
                      disabled={isUploading}
                    >
                      {isUploading ? "Uploading..." : "Save changes"}
                    </Button>
                  </DialogFooter>
                </DialogContent>
              </Dialog>
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="flex items-center gap-3">
              <User className="text-muted-foreground h-5 w-5" />
              <div>
                <p className="text-muted-foreground text-sm">Gender</p>
                <p>
                  {personalInfo.gender &&
                    personalInfo?.gender.charAt(0).toUpperCase() +
                      personalInfo?.gender.slice(1)}
                </p>
              </div>
            </div>
            <div className="flex items-center gap-3">
              <Calendar className="text-muted-foreground h-5 w-5" />
              <div>
                <p className="text-muted-foreground text-sm">Birthday</p>
                <p>{personalInfo.date_of_birth}</p>
              </div>
            </div>
            <div className="flex items-center gap-3">
              <Phone className="text-muted-foreground h-5 w-5" />
              <div>
                <p className="text-muted-foreground text-sm">Phone Number</p>
                <p>{user?.phone_number}</p>
              </div>
            </div>
            <div className="flex items-center gap-3">
              <Mail className="text-muted-foreground h-5 w-5" />
              <div>
                <p className="text-muted-foreground text-sm">Email</p>
                <p>{user?.email}</p>
              </div>
            </div>
            <div className="flex items-center gap-3">
              <Mail className="text-muted-foreground h-5 w-5" />
              <div>
                <p className="text-muted-foreground text-sm">Address</p>
                <p>{personalInfo?.address || "Not Provided"}</p>
              </div>
            </div>
            <div className="flex items-center gap-3">
              <Mail className="text-muted-foreground h-5 w-5" />
              <div>
                <p className="text-muted-foreground text-sm">Medical History</p>
                <p>{personalInfo?.medical_history}</p>
              </div>
            </div>
            <div className="flex items-center gap-3">
              <Mail className="text-muted-foreground h-5 w-5" />
              <div>
                <p className="text-muted-foreground text-sm">Weight</p>
                <p>{personalInfo?.weight || "Not Provided"} kg</p>
              </div>
            </div>
            <div className="flex items-center gap-3">
              <Mail className="text-muted-foreground h-5 w-5" />
              <div>
                <p className="text-muted-foreground text-sm">Blood Group</p>
                <p>{personalInfo?.blood_group || "Not Provided"}</p>
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
