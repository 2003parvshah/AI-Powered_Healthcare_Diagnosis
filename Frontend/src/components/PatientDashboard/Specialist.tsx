import { useEffect, useState } from "react";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import DoctorFilterDropdowns from "./FilterDoctors";
import { Separator } from "../ui/separator";
import { DoctorDialog } from "./DoctorDialog";
import { useSelector } from "react-redux";
import { RootState } from "@/redux/store";
import axios from "axios";
interface doctorInterface {
  id: number;
  name: string;
  specialty: string;
  consultation_fees: number;
  experience: number;
  profile_photo: string;
}
// const doctors = [
//   { name: "Dr. Jennie Kim", specialty: "Dermatology", price: 110 },
//   { name: "Prof. Dr. Niall Horan", specialty: "Psychiatry", price: 36 },
//   { name: "Dr. Alexandra Boje", specialty: "Cardiology", price: 200 },
//   { name: "Dr. Veronica Nguyen", specialty: "Gynecology", price: 36 },
//   { name: "Dr. Adam Hall", specialty: "Orthopedics", price: 360 },
//   { name: "Prof. Dr. Dirly Sanders", specialty: "Anesthesiology", price: 7006 },
// ];

export const Specialist = () => {
  const token = useSelector((state: RootState) => state.auth.token);
  const [selectedSpecialization, setSelectedSpecialization] = useState<
    string | null
  >(null);
  const [selectedPriceRange, setSelectedPriceRange] = useState<string | null>(
    null,
  );
  const [doctors, setDoctors] = useState<doctorInterface[]>();
  useEffect(() => {
    const fetchDoctors = async () => {
      try {
        const response = await axios.get(
          `${import.meta.env.VITE_BASE_URL}/patient/getAllDoctorDetails`,
          {
            headers: { Authorization: `Bearer ${token}` },
          },
        );
        console.log(response);
        setDoctors(response.data);
      } catch (error) {
        console.log(error);
      }
    };
    fetchDoctors();
  }, [token]);

  // Function to filter doctors
  const filteredDoctors = doctors?.filter((doctor) => {
    const matchesSpecialization =
      !selectedSpecialization ||
      doctor.specialty.toLowerCase() === selectedSpecialization;

    const matchesPrice =
      !selectedPriceRange ||
      (selectedPriceRange === "0-50" &&
        doctor.consultation_fees >= 0 &&
        doctor.consultation_fees <= 50) ||
      (selectedPriceRange === "51-100" &&
        doctor.consultation_fees > 50 &&
        doctor.consultation_fees <= 100) ||
      (selectedPriceRange === "101-150" &&
        doctor.consultation_fees > 100 &&
        doctor.consultation_fees <= 150) ||
      (selectedPriceRange === "151-200" &&
        doctor.consultation_fees > 150 &&
        doctor.consultation_fees <= 200) ||
      (selectedPriceRange === "200+" && doctor.consultation_fees > 200);

    return matchesSpecialization && matchesPrice;
  });

  return (
    <section className="px-3">
      <div className="flex items-center justify-between">
        <h2 className="text-2xl font-semibold">Welcome, Audrey!</h2>
      </div>
      <p className="mt-2 text-gray-500">Find the best doctors here</p>

      <div className="flex items-start justify-end">
        <DoctorFilterDropdowns
          onSpecializationChange={setSelectedSpecialization}
          onPriceRangeChange={setSelectedPriceRange}
        />
      </div>

      <h3 className="mt-6 text-xl font-semibold">
        Recommended Doctors ({filteredDoctors?.length})
      </h3>
      <div className="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        {filteredDoctors?.map((doctor, index) => (
          <Card key={index} className="flex max-w-2xl flex-col">
            <CardHeader className="flex gap-4 p-2">
              <div className="flex flex-row items-stretch justify-start gap-4">
                <img
                  src={`${doctor.profile_photo}`}
                  alt={doctor.name}
                  className="aspect-square w-16 rounded-full object-cover"
                />
                <div className="flex flex-col justify-evenly">
                  <CardTitle className="text-lg font-medium">
                    {doctor.name}
                  </CardTitle>
                  <CardDescription className="text-muted-foreground">
                    {doctor.specialty}
                  </CardDescription>
                </div>
              </div>
            </CardHeader>
            <CardContent className="bg-secondary m-2 flex flex-col gap-1 rounded p-2">
              <div className="flex flex-row items-center justify-between p-2">
                <div className="font-semibold">
                  <p>{doctor.experience} years</p>
                  <p className="text-muted-foreground font-normal">
                    Experience
                  </p>
                </div>
                <Separator orientation="vertical" />
                <div>
                  <p className="text-foreground text-xl font-bold">
                    ${doctor.consultation_fees}
                  </p>
                  <p className="text-muted-foreground">Consultation fee</p>
                </div>
              </div>
              <DoctorDialog id={doctor.id} />
            </CardContent>
          </Card>
        ))}
      </div>
    </section>
  );
};
