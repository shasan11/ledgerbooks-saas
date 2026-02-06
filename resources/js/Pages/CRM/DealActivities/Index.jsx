import React from "react";
import { usePage } from "@inertiajs/react";
import ReusableCrudInertia from "@/Components/ReusableCrud";
import * as Yup from "yup";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

const typeOptions = [
  { value: "call", label: "Call" },
  { value: "meeting", label: "Meeting" },
  { value: "task", label: "Task" },
  { value: "email", label: "Email" },
  { value: "note", label: "Note" },
];

const statusOptions = [
  { value: "pending", label: "Pending" },
  { value: "done", label: "Done" },
  { value: "cancelled", label: "Cancelled" },
];

export default function Index() {
  const { items, inactiveItems } = usePage().props;

  return (
    <AuthenticatedLayout>
      <ReusableCrudInertia
        indexUrl="/crm/activity"
        storeUrl="/crm/activity"
        updateUrl={(id) => `/crm/activity/${id}`}
        destroyUrl={(id) => `/crm/activity/${id}`}
        bulkUrl="/crm/activity/bulk"
        title="Deal Activity"
        columns={[
          { title: "Subject", dataIndex: "subject", sorter: true, field: "subject" },
          { title: "Type", dataIndex: "type", sorter: true, field: "type" },
          { title: "Status", dataIndex: "status", sorter: true, field: "status" },
          { title: "Active", dataIndex: "active", render: (v) => (v ? "Yes" : "No") },
        ]}
        fields={[
          { type: "select", name: "type", label: "Type", options: typeOptions, required: true, col: 12 },
          { type: "text", name: "subject", label: "Subject", required: true, col: 12 },
          { type: "number", name: "contact_id", label: "Contact ID", col: 12 },
          { type: "number", name: "deal_id", label: "Deal ID", col: 12 },
          { type: "date", name: "due_at", label: "Due Date", col: 12 },
          { type: "date", name: "completed_at", label: "Completed At", col: 12 },
          { type: "select", name: "status", label: "Status", options: statusOptions, col: 12 },
          { type: "text", name: "assigned_to_id", label: "Assigned To", col: 12 },
          { type: "textarea", name: "description", label: "Description", col: 24 },
          { type: "switch", name: "active", label: "Active", col: 24 },
        ]}
        validationSchema={Yup.object({
          type: Yup.string().required("Required"),
          subject: Yup.string().required("Required"),
        })}
        crudInitialValues={{
          type: "task",
          subject: "",
          contact_id: "",
          deal_id: "",
          due_at: "",
          completed_at: "",
          status: "pending",
          assigned_to_id: "",
          description: "",
          active: true,
        }}
        enableInactiveDrawer={true}
        form_ui="modal"
        drawerWidth={1200}
      />
    </AuthenticatedLayout>
  );
}
