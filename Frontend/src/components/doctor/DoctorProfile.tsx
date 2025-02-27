import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Badge } from "@/components/ui/badge";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { useEffect, useState } from "react";
import axios from "axios";
import { useSelector } from "react-redux";
import { RootState } from "@/redux/store";
import { DoctorPersonalInfo } from "@/interface/doctor/personalInfo";
import { Pencil } from "lucide-react";
import { Button } from "@/components/ui/button";
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
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

export const DoctorProfile = () => {
  const token = useSelector((state: RootState) => state.auth.token);

  // State for doctor details
  const [personalInfo, setPersonalInfo] = useState<Partial<DoctorPersonalInfo>>(
    {},
  );
  const [workInfo, setWorkInfo] = useState<Partial<DoctorPersonalInfo>>({});
  const [feeInfo, setFeeInfo] = useState<Partial<DoctorPersonalInfo>>({});
  const [editData, setEditData] = useState<Partial<DoctorPersonalInfo>>({});
  const [editWorkData, setEditWorkData] = useState<Partial<DoctorPersonalInfo>>(
    {},
  );
  const [editFeeData, setEditFeeData] = useState<Partial<DoctorPersonalInfo>>(
    {},
  );
  // const [isEditing, setIsEditing] = useState(false);
  const [isEditingProfile, setIsEditingProfile] = useState(false);
  const [isEditingWork, setIsEditingWork] = useState(false);
  const [isEditingFees, setIsEditingFees] = useState(false);
  const [isUploading, setIsUploading] = useState(false);
  // Fetch Doctor Data
  useEffect(() => {
    const fetchDoctorDetails = async () => {
      try {
        const responses = await Promise.allSettled([
          axios.get(
            `${import.meta.env.VITE_BASE_URL}/doctor/getDoctorPersonalInfo`,
            {
              headers: {
                Accept: "application/json",
                Authorization: `Bearer ${token}`,
              },
            },
          ),
          axios.get(
            `${import.meta.env.VITE_BASE_URL}/doctor/getDoctorWorkExperience`,
            {
              headers: {
                Accept: "application/json",
                Authorization: `Bearer ${token}`,
              },
            },
          ),
          axios.get(`${import.meta.env.VITE_BASE_URL}/doctor/getDoctorFees`, {
            headers: {
              Accept: "application/json",
              Authorization: `Bearer ${token}`,
            },
          }),
        ]);

        const [personalResponse, workResponse, feeResponse] = responses;

        if (personalResponse.status === "fulfilled") {
          setPersonalInfo(personalResponse.value.data.data);
          setEditData(personalResponse.value.data.data); // Pre-fill the edit form
        } else {
          console.error(
            "Error fetching personal details:",
            personalResponse.reason,
          );
        }

        if (workResponse.status === "fulfilled") {
          setWorkInfo(workResponse.value.data.data);
          setEditWorkData(workResponse.value.data.data);
        } else {
          console.error("Error fetching work experience:", workResponse.reason);
        }

        if (feeResponse.status === "fulfilled") {
          setFeeInfo(feeResponse.value.data.data);
          setEditFeeData(feeResponse.value.data.data);
        } else {
          console.error("Error fetching fee details:", feeResponse.reason);
        }
      } catch (err) {
        console.error("Unexpected error fetching doctor details:", err);
      }
    };

    fetchDoctorDetails();
  }, [token]);
  // Handle update API call
  // const handleUpdateProfile = async () => {
  //   console.log(editData);
  //   try {
  //     await axios.post(
  //       `${import.meta.env.VITE_BASE_URL}/doctor/setDoctorPersonalInfo`,
  //       editData,
  //       {
  //         headers: {
  //           Accept: "application/json",
  //           Authorization: `Bearer ${token}`,
  //         },
  //       },
  //     );

  //     setPersonalInfo(editData as DoctorPersonalInfo);
  //     setIsEditing(false);
  //   } catch (err) {
  //     console.error("Error updating profile:", err);
  //   }
  // };
  const handleUpdateProfile = async () => {
    setIsUploading(true);
    console.log(editData);

    try {
      const formData = new FormData();
      formData.append("full_name", editData.full_name || "");
      formData.append("date_of_birth", editData.date_of_birth || "");
      formData.append("gender", editData.gender || "");
      formData.append("nationality", editData.nationality || "");
      formData.append("languages_spoken", editData.languages_spoken || "");

      if (editData.profile_photo instanceof File) {
        formData.append("profile_photo", editData.profile_photo);
      }

      const response = await axios.post(
        `${import.meta.env.VITE_BASE_URL}/doctor/setDoctorPersonalInfo`,
        formData,
        {
          headers: {
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
          },
        },
      );

      if (response.status === 201) {
        setPersonalInfo(response.data.data);
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
  const handleUpdateWorkProfile = async () => {
    setIsUploading(true);
    console.log(editWorkData);

    try {
      const formData = new FormData();
      formData.append(
        "current_hospital_clinic",
        editWorkData.current_hospital_clinic || "",
      );
      formData.append(
        "previous_workplaces",
        editWorkData.previous_workplaces || "",
      );
      formData.append(
        "internship_residency_details",
        editWorkData.internship_residency_details || "",
      );
      formData.append("experience", editWorkData.experience || "");

      const response = await axios.post(
        `${import.meta.env.VITE_BASE_URL}/doctor/setDoctorWorkExperience`,
        formData,
        {
          headers: {
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
          },
        },
      );

      if (response.status === 201) {
        setWorkInfo(response.data.data);
      } else {
        console.error("Error updating profile:", response.statusText);
      }
    } catch (err) {
      console.error("Error updating profile:", err);
    } finally {
      setIsUploading(false);
      setIsEditingWork(false);
    }
  };
  const handleUpdateFee = async () => {
    setIsUploading(true);
    // console.log(editWorkData);

    try {
      const formData = new FormData();
      formData.append(
        "consultation_fees",
        editFeeData.consultation_fees?.toString() || "",
      );
      formData.append(
        "payment_methods_accepted",
        editFeeData.payment_methods_accepted || "",
      );

      const response = await axios.post(
        `${import.meta.env.VITE_BASE_URL}/doctor/setDoctorFees`,
        formData,
        {
          headers: {
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
          },
        },
      );
      console.log(response);

      if (response.status === 201) {
        setFeeInfo(response.data.data);
      } else {
        console.error("Error updating profile:", response.statusText);
      }
    } catch (err) {
      console.error("Error updating profile:", err);
    } finally {
      setIsUploading(false);
      setIsEditingFees(false);
    }
  };

  return (
    <section className="flex max-w-7xl flex-col items-start justify-start space-y-6 p-4">
      {/* Doctor Profile Header */}
      <div className="mb-6 flex items-center gap-4">
        <Avatar className="h-16 w-16">
          <AvatarImage
            className="object-cover"
            src={
              typeof personalInfo?.profile_photo === "string"
                ? personalInfo.profile_photo
                : undefined
            }
            alt="Doctor Profile"
          />
          <AvatarFallback>Dr</AvatarFallback>
        </Avatar>
        <div>
          <div className="flex items-center gap-2">
            <h1 className="text-2xl font-bold">
              {personalInfo?.full_name || "Doctor"}
            </h1>
            <Badge variant="outline" className="bg-green-100 text-emerald-700">
              DOCTOR
            </Badge>
          </div>
          <p className="text-muted-foreground text-sm">
            Joined Since:{" "}
            {personalInfo?.created_at
              ? new Date(personalInfo.created_at).toDateString()
              : "N/A"}
          </p>
        </div>
      </div>

      {/* Profile Information Cards */}
      <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
        {/* Personal Information */}
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center justify-between">
              Basic Information{" "}
              <Dialog
                open={isEditingProfile}
                onOpenChange={setIsEditingProfile}
              >
                <DialogTrigger asChild>
                  <Button size="sm" variant="outline">
                    <Pencil size={15} />
                  </Button>
                </DialogTrigger>
                <DialogContent className="sm:max-w-[500px]">
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
                        value={editData.full_name || ""}
                        className="col-span-3"
                        onChange={(e) =>
                          setEditData({
                            ...editData,
                            full_name: e.target.value,
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
                            gender: value.toLowerCase(),
                          })
                        }
                        defaultValue={`${editData.gender?.toLowerCase()}`} // to update after api updatation
                      >
                        <SelectTrigger>
                          <SelectValue placeholder="male" />
                        </SelectTrigger>
                        <SelectContent id="gender">
                          <SelectItem value="male">Male</SelectItem>
                          <SelectItem value="female">Female</SelectItem>
                        </SelectContent>
                      </Select>
                    </div>
                    <div>
                      <Label htmlFor="nationality" className="text-right">
                        Nationality
                      </Label>
                      <Input
                        type="text"
                        id="nationality"
                        value={editData.nationality || ""}
                        className="col-span-3"
                        onChange={(e) =>
                          setEditData({
                            ...editData,
                            nationality: e.target.value,
                          })
                        }
                      />
                    </div>
                    <div>
                      <Label htmlFor="languages" className="text-right">
                        Languages
                      </Label>
                      <Input
                        type="text"
                        id="languages"
                        value={editData.languages_spoken || ""}
                        className="col-span-3"
                        onChange={(e) =>
                          setEditData({
                            ...editData,
                            languages_spoken: e.target.value,
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
            <InfoRow label="Gender" value={personalInfo?.gender} />
            <InfoRow label="Birthday" value={personalInfo?.date_of_birth} />
            <InfoRow label="Nationality" value={personalInfo?.nationality} />
            <InfoRow
              label="Languages Spoken"
              value={personalInfo?.languages_spoken}
            />
          </CardContent>
        </Card>

        {/* Work Information */}
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center justify-between">
              Work Information{" "}
              <Dialog open={isEditingWork} onOpenChange={setIsEditingWork}>
                <DialogTrigger asChild>
                  <Button size="sm" variant="outline">
                    <Pencil size={15} />
                  </Button>
                </DialogTrigger>
                <DialogContent className="sm:max-w-[500px]">
                  <DialogHeader>
                    <DialogTitle>Edit Work Experience</DialogTitle>
                    <DialogDescription>
                      Update your experience details and save the changes.
                    </DialogDescription>
                  </DialogHeader>
                  <div className="grid gap-4 py-4">
                    <div>
                      <Label htmlFor="experience" className="text-right">
                        experience
                      </Label>
                      <Input
                        id="experience"
                        value={editWorkData.experience || ""}
                        className="col-span-3"
                        onChange={(e) =>
                          setEditWorkData({
                            ...editWorkData,
                            experience: e.target.value,
                          })
                        }
                      />
                    </div>
                    <div>
                      <Label
                        htmlFor="current_hospital_clinic"
                        className="text-right"
                      >
                        current_hospital_clinic
                      </Label>
                      <Input
                        id="current_hospital_clinic"
                        value={editWorkData.current_hospital_clinic || ""}
                        className="col-span-3"
                        onChange={(e) =>
                          setEditWorkData({
                            ...editWorkData,
                            current_hospital_clinic: e.target.value,
                          })
                        }
                      />
                    </div>
                    <div>
                      <Label
                        htmlFor="previous_workplaces"
                        className="text-right"
                      >
                        previous_workplaces
                      </Label>
                      <Input
                        id="previous_workplaces"
                        value={editWorkData.previous_workplaces || ""}
                        className="col-span-3"
                        onChange={(e) =>
                          setEditWorkData({
                            ...editWorkData,
                            previous_workplaces: e.target.value,
                          })
                        }
                      />
                    </div>
                    <div>
                      <Label
                        htmlFor="internship_residency_details"
                        className="text-right"
                      >
                        internship_residency_details
                      </Label>
                      <Input
                        id="internship_residency_details"
                        value={editWorkData.internship_residency_details || ""}
                        className="col-span-3"
                        onChange={(e) =>
                          setEditWorkData({
                            ...editWorkData,
                            internship_residency_details: e.target.value,
                          })
                        }
                      />
                    </div>
                  </div>
                  <DialogFooter>
                    <Button
                      onClick={handleUpdateWorkProfile}
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
            <InfoRow label="Experience" value={workInfo.experience} />
            <InfoRow
              label="current_hospital_clinic"
              value={workInfo?.current_hospital_clinic}
            />
            <InfoRow
              label="previous_workplaces"
              value={workInfo?.previous_workplaces}
            />
            <InfoRow
              label="internship_residency_details"
              value={workInfo?.internship_residency_details}
            />
          </CardContent>
        </Card>
        {/* Fee Info */}
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center justify-between">
              Fees Information{" "}
              <Dialog open={isEditingFees} onOpenChange={setIsEditingFees}>
                <DialogTrigger asChild>
                  <Button size="sm" variant="outline">
                    <Pencil size={15} />
                  </Button>
                </DialogTrigger>
                <DialogContent className="sm:max-w-[500px]">
                  <DialogHeader>
                    <DialogTitle>Edit Fees</DialogTitle>
                    <DialogDescription>
                      Update your Fees details and save the changes.
                    </DialogDescription>
                  </DialogHeader>
                  <div className="grid gap-4 py-4">
                    <div>
                      <Label htmlFor="consultation_fees" className="text-right">
                        consultation_fees
                      </Label>
                      <Input
                        id="consultation_fees"
                        value={editFeeData.consultation_fees || ""}
                        className="col-span-3"
                        onChange={(e) =>
                          setEditFeeData({
                            ...editFeeData,
                            consultation_fees: parseInt(e.target.value),
                          })
                        }
                      />
                    </div>
                    <div>
                      <Label
                        htmlFor="payment_methods_accepted"
                        className="text-right"
                      >
                        payment_methods_accepted
                      </Label>
                      <Input
                        id="payment_methods_accepted"
                        value={editFeeData.payment_methods_accepted || ""}
                        className="col-span-3"
                        onChange={(e) =>
                          setEditFeeData({
                            ...editFeeData,
                            payment_methods_accepted: e.target.value,
                          })
                        }
                      />
                    </div>
                  </div>
                  <DialogFooter>
                    <Button onClick={handleUpdateFee} disabled={isUploading}>
                      {isUploading ? "Uploading..." : "Save changes"}
                    </Button>
                  </DialogFooter>
                </DialogContent>
              </Dialog>
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <InfoRow
              label="consultation_fees"
              value={feeInfo?.consultation_fees}
            />
            <InfoRow
              label="payment_methods_accepted"
              value={feeInfo?.payment_methods_accepted}
            />
          </CardContent>
        </Card>
      </div>
    </section>
  );
};

// **Reusable Components for Cleaner Code**
const InfoRow = ({
  label,
  value,
}: {
  label: string;
  value?: string | number;
}) => (
  <div className="flex items-center gap-3">
    <div>
      <p className="text-muted-foreground text-sm">{label}</p>
      <p>{value || "N/A"}</p>
    </div>
  </div>
);
