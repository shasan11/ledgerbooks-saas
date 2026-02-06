import React, { useMemo } from "react";
import { usePage } from "@inertiajs/react";
import * as Yup from "yup";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import ReusableCrudInertia from "@/Components/ReusableCrud";

export default function EmployeesIndex() {
  const { meta } = usePage().props;

  const departmentOptions = useMemo(
    () => (meta?.departments || []).map((department) => ({ value: department, label: department })),
    [meta?.departments]
  );

  const designationOptions = useMemo(
    () => (meta?.designations || []).map((designation) => ({ value: designation, label: designation })),
    [meta?.designations]
  );

  const employmentTypeOptions = useMemo(
    () => (meta?.employmentTypes || []).map((type) => ({ value: type, label: type })),
    [meta?.employmentTypes]
  );

  const statusOptions = useMemo(
    () => (meta?.statuses || []).map((status) => ({ value: status, label: status })),
    [meta?.statuses]
  );

  const genderOptions = useMemo(
    () => (meta?.genders || []).map((gender) => ({ value: gender, label: gender })),
    [meta?.genders]
  );

  const columns = useMemo(
    () => [
      { title: "Employee ID", dataIndex: "employee_id", sorter: true, field: "employee_id" },
      {
        title: "Name",
        dataIndex: "first_name",
        sorter: true,
        field: "first_name",
        render: (_, record) => `${record.first_name || ""} ${record.last_name || ""}`.trim(),
      },
      { title: "Department", dataIndex: "department", sorter: true, field: "department" },
      { title: "Designation", dataIndex: "designation", sorter: true, field: "designation" },
      { title: "Employment Type", dataIndex: "employment_type", sorter: true, field: "employment_type" },
      { title: "Status", dataIndex: "status", sorter: true, field: "status" },
      { title: "Email", dataIndex: "email" },
      { title: "Phone", dataIndex: "phone" },
      { title: "Hire Date", dataIndex: "hire_date", sorter: true, field: "hire_date" },
    ],
    []
  );

  const fields = useMemo(
    () => [
      { type: "text", name: "employee_id", label: "Employee ID", required: true, col: 12 },
      { type: "text", name: "first_name", label: "First Name", required: true, col: 12 },
      { type: "text", name: "last_name", label: "Last Name", required: true, col: 12 },
      { type: "text", name: "email", label: "Email", col: 12 },
      { type: "text", name: "phone", label: "Phone", col: 12 },
      { type: "select", name: "gender", label: "Gender", options: genderOptions, col: 12 },
      { type: "date", name: "date_of_birth", label: "Date of Birth", col: 12 },
      { type: "date", name: "hire_date", label: "Hire Date", required: true, col: 12 },
      { type: "select", name: "department", label: "Department", options: departmentOptions, col: 12 },
      { type: "select", name: "designation", label: "Designation", options: designationOptions, col: 12 },
      {
        type: "select",
        name: "employment_type",
        label: "Employment Type",
        options: employmentTypeOptions,
        required: true,
        col: 12,
      },
      { type: "select", name: "status", label: "Status", options: statusOptions, col: 12 },
      { type: "number", name: "basic_salary", label: "Basic Salary", min: 0, col: 12 },
      { type: "text", name: "tax_id", label: "Tax ID", col: 12 },
      { type: "textarea", name: "address", label: "Address", col: 24, rows: 2 },
      { type: "text", name: "emergency_contact_name", label: "Emergency Contact Name", col: 12 },
      { type: "text", name: "emergency_contact_phone", label: "Emergency Contact Phone", col: 12 },
      { type: "text", name: "bank_name", label: "Bank Name", col: 12 },
      { type: "text", name: "bank_account_number", label: "Bank Account Number", col: 12 },
      { type: "switch", name: "active", label: "Active", col: 12 },
    ],
    [departmentOptions, designationOptions, employmentTypeOptions, genderOptions, statusOptions]
  );

  const validationSchema = Yup.object({
    employee_id: Yup.string().required("Required"),
    first_name: Yup.string().required("Required"),
    last_name: Yup.string().required("Required"),
    email: Yup.string()
      .transform((value) => (value === "" ? null : value))
      .nullable()
      .email("Invalid email"),
    hire_date: Yup.string().required("Required"),
    employment_type: Yup.string().required("Required"),
  });

  const initialValues = {
    employee_id: "",
    first_name: "",
    last_name: "",
    email: "",
    phone: "",
    gender: "",
    date_of_birth: "",
    hire_date: "",
    department: "",
    designation: "",
    employment_type: "",
    status: "",
    basic_salary: null,
    address: "",
    emergency_contact_name: "",
    emergency_contact_phone: "",
    bank_name: "",
    bank_account_number: "",
    tax_id: "",
    active: true,
  };

  return (
    <AuthenticatedLayout>
      <ReusableCrudInertia
        indexUrl="/hrm/employees"
        storeUrl="/hrm/employees"
        updateUrl={(id) => `/hrm/employees/${id}`}
        destroyUrl={(id) => `/hrm/employees/${id}`}
        bulkUrl="/hrm/employees/bulk"
        title="Employees"
        columns={columns}
        fields={fields}
        validationSchema={validationSchema}
        crudInitialValues={initialValues}
        searchParam="q"
        enableInactiveDrawer={true}
        form_ui="modal"
        drawerWidth={1200}
      />
    </AuthenticatedLayout>
  );
}
