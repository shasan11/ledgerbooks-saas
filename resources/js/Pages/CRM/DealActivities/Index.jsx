import React, { useMemo } from "react";
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
  const { items, inactiveItems, contacts = [], deals = [], users = [] } = usePage().props;

  const contactOptions = useMemo(
    () =>
      contacts.map((contact) => ({
        value: contact.id,
        label: contact.code ? `${contact.name} (${contact.code})` : contact.name,
      })),
    [contacts]
  );

  const dealOptions = useMemo(
    () =>
      deals.map((deal) => ({
        value: deal.id,
        label: deal.code ? `${deal.title} (${deal.code})` : deal.title,
      })),
    [deals]
  );

  const userOptions = useMemo(
    () =>
      users.map((user) => ({
        value: user.id,
        label: user.name || user.email || user.id,
      })),
    [users]
  );

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
        ]}
        fields={[
          { type: "select", name: "type", label: "Type", options: typeOptions, required: true, col: 12 },
          { type: "text", name: "subject", label: "Subject", required: true, col: 12 },
          { type: "select", name: "contact_id", label: "Contact", options: contactOptions, col: 12 },
          { type: "select", name: "deal_id", label: "Deal", options: dealOptions, col: 12 },
          { type: "date", name: "due_at", label: "Due Date", col: 12 },
          { type: "date", name: "completed_at", label: "Completed At", col: 12 },
          { type: "select", name: "status", label: "Status", options: statusOptions, col: 12 },
          { type: "select", name: "assigned_to_id", label: "Assigned To", options: userOptions, col: 12 },
          { type: "textarea", name: "description", label: "Description", col: 24 },
        ]}
        validationSchema={Yup.object({
          type: Yup.string().required("Required"),
          subject: Yup.string().required("Required"),
        })}
        crudInitialValues={{
          type: "task",
          subject: "",
          contact_id: null,
          deal_id: null,
          due_at: "",
          completed_at: "",
          status: "pending",
          assigned_to_id: null,
          description: "",
        }}
        enableInactiveDrawer={true}
        form_ui="modal"
        drawerWidth={1200}
      />
    </AuthenticatedLayout>
  );
}
