import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import { Pencil } from "lucide-react";
import { Button } from "@/components/ui/button";
import { useState } from "react";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { DoctorPersonalInfo } from "@/interface/doctor/personalInfo";

const PersonalInfoEditDialog = ({
  data,
  setData,
}: {
  data: Partial<DoctorPersonalInfo>;
  setData: () => void;
}) => {
  const [editData, setEditData] = useState(data || {});
  const [isEditing, setIsEditing] = useState(false);
  const [isUploading, setIsUploading] = useState(false);

  const handleSave = () => {
    setIsUploading(true);
    setData();
    setIsEditing(false);
    setIsUploading(false);
  };

  return (
    <Dialog open={isEditing} onOpenChange={setIsEditing}>
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
                  profile_photo: e.target.files ? e.target.files[0] : undefined,
                })
              }
            />
          </div>
        </div>
        <DialogFooter>
          <Button onClick={handleSave} disabled={isUploading}>
            {isUploading ? "Uploading..." : "Save changes"}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
};

export default PersonalInfoEditDialog;
