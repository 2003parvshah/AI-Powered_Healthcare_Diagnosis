import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
// import { Input } from "@/components/ui/input";
import DoctorFilterDropdowns from "./FilterDoctors";

const doctors = [
  { name: "Dr. Jennie Kim", specialty: "Orthopedic", price: "$36/h" },
  {
    name: "Prof. Dr. Niall Horan",
    specialty: "Orthopedic",
    price: "$36/h",
  },
  { name: "Dr. Alexandra Boje", specialty: "Orthopedic", price: "$36/h" },
  { name: "Dr. Veronica Nguyen", specialty: "Orthopedic", price: "$36/h" },
  { name: "Dr. Adam Hall", specialty: "Orthopedic", price: "$36/h" },
  { name: "Prof. Dr. Dirly Sanders", specialty: "Orthopedic", price: "$36/h" },
];

export const Specialist = () => {
  return (
    <div className="px-3">
      <div className="flex items-center justify-between">
        <h2 className="text-2xl font-semibold">Welcome, Audrey!</h2>
      </div>
      <p className="mt-2 text-gray-500">Find the best doctors here</p>
      <div className="mt-4 flex items-start justify-end">
        <div className="flex items-center justify-center gap-3">
          {/* <div className="flex items-center justify-center gap-3 rounded-md border px-3 py-1 shadow">
            <Search className="text-sm" size={20} />
            <Input
              placeholder="Search"
              className="h-auto border-0 p-0 shadow-none focus-visible:ring-0"
            />
          </div> */}
          <DoctorFilterDropdowns />
        </div>
      </div>

      <h3 className="mt-6 text-xl font-semibold">
        Recommended Orthopedic (27)
      </h3>
      <div className="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        {doctors.map((doctor, index) => (
          <Card key={index} className="flex max-w-2xl flex-col">
            <CardHeader className="p-2">
              <img
                src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                alt={doctor.name}
                className="rounded"
              />
            </CardHeader>
            <CardContent className="flex flex-col gap-1 px-3 py-2">
              <CardTitle className="text-xl font-medium">
                {doctor.name}
              </CardTitle>
              <CardDescription>{doctor.specialty}</CardDescription>
              <div className="text-primary text-2xl font-bold">
                {doctor.price}
              </div>
            </CardContent>
          </Card>
        ))}
      </div>
    </div>
  );
};
