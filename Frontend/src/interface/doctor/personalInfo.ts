export interface DoctorPersonalInfo {
  id: number;
  doctor_id: number;
  full_name: string;
  date_of_birth: string;
  gender: string;
  profile_photo?: string | File;
  nationality: string;
  languages_spoken: string;
  created_at?: Date;
  current_hospital_clinic: string;
  experience: string;
  previous_workplaces: string;
  internship_residency_details: string;
  consultation_fees: number;
  payment_methods_accepted: string;
}
