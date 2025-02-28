import { useState, useEffect } from "react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Avatar, AvatarImage } from "@/components/ui/avatar";
import { File, FileImage, Mail, Phone } from "lucide-react";
import { useSelector } from "react-redux";
import { RootState } from "@/redux/store";
import axios from "axios";

interface HealthIssue {
  diagnosis: string;
  symptoms: string;
  solution: string;
  doctorType: string;
  otherInfo: string;
}

interface Patient {
  name: string;
  date_of_birth: string;
  gender: string;
  address: string;
  profile_photo: string;
  past_medical_conditions: string;
  allergies: string;
  blood_pressure: string;
  weight: string;
  blood_group: string;
  medical_history: string;
  health_issues: HealthIssue[];
}

export const PatientDetails = ({
  event,
}: {
  event: { id: string; start: Date; end: Date; title: string; color: string };
}) => {
  const token = useSelector((state: RootState) => state.auth.token);
  const [patient, setPatient] = useState<Patient | null>(null);

  useEffect(() => {
    const fetchPatientDetails = async () => {
      try {
        const response = await axios.post(
          `${import.meta.env.VITE_BASE_URL}/doctor/getPatientWithHealthIssues`,
          { id: event.id },
          { headers: { Authorization: `Bearer ${token}` } },
        );
        console.log(response);

        setPatient(response.data.patient);
      } catch (error) {
        console.log(error);
      }
    };
    fetchPatientDetails();
  }, [event.id, token]);

  if (!patient) return <p>Loading...</p>;

  return (
    <div className="flex flex-col gap-4">
      <p className="text-lg font-semibold">Personal Detail</p>
      <Card>
        <CardHeader>
          <div className="flex items-center justify-start gap-3">
            <Avatar className="h-12 w-12">
              <AvatarImage
                className="object-cover"
                src={patient.profile_photo}
              />
            </Avatar>
            <div>
              <p className="text-lg font-semibold">{event.title} </p>
              <div className="text-muted-foreground flex gap-1 text-sm">
                <p>
                  <Phone size={15} className="inline" /> 987456321
                </p>
                •
                <p>
                  <Mail size={15} className="inline" /> m@gmail.com
                </p>
              </div>
            </div>
          </div>
          <div>
            <ul className="mt-5 flex flex-col gap-3 text-sm font-semibold [&_span]:font-normal">
              <li>
                DOB: <span>{patient.date_of_birth}</span>
              </li>
              <li>
                Gender: <span>{patient.gender}</span>
              </li>
              <li>
                Medical History: <span>{patient.medical_history}</span>
              </li>
              <li>
                Address: <span>{patient.address}</span>
              </li>
              <li>
                Weight: <span>{patient.weight}</span>
              </li>
              <li>
                Blood Group: <span>{patient.blood_group}</span>
              </li>
            </ul>
          </div>
        </CardHeader>
      </Card>
      {patient.health_issues && (
        <>
          <p className="text-lg font-semibold">Ai Generated Diagnoses</p>
          <Card>
            <CardHeader>
              <CardTitle>Health Issues</CardTitle>
            </CardHeader>
            <CardContent className="flex flex-col gap-4">
              {patient.health_issues.map((issue, idx) => (
                <div key={idx} className="border-b pb-2 last:border-0">
                  <p className="font-medium text-blue-600">{issue.diagnosis}</p>
                  <p className="text-sm text-gray-500">
                    Symptoms: {issue.symptoms}
                  </p>
                  <p className="text-sm text-gray-500">
                    Solution: {issue.solution}
                  </p>
                  <p className="text-sm text-gray-500">
                    Doctor Type: {issue.doctorType}
                  </p>
                  <p className="text-sm text-gray-500">
                    Other Info: {issue.otherInfo}
                  </p>
                  <div>
                    <Button variant="ghost" size="sm">
                      <File /> PDF
                    </Button>
                    <Button variant="ghost" size="sm">
                      <FileImage /> Image
                    </Button>
                  </div>
                </div>
              ))}
            </CardContent>
          </Card>
        </>
      )}

      <Button>Reschedule</Button>
    </div>
  );
};
