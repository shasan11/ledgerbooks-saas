import React, { useMemo, useState } from "react";
import { Head, router, usePage } from "@inertiajs/react";

export default function BranchesIndex() {
  const { branches, query, meta, flash } = usePage().props;

  const [selectedIds, setSelectedIds] = useState([]);
  const [formOpen, setFormOpen] = useState(false);
  const [editing, setEditing] = useState(null);

  const [form, setForm] = useState(() => ({
    code: "",
    name: "",
    email: "",
    phone: "",
    address: "",
    country: "",
    city: "",
    timezone: "",
    currency_id: "",
    is_head_office: false,
    active: true,
  }));

  const applyQuery = (next) => {
    router.get("/branches", { ...query, ...next }, { preserveState: true, replace: true });
  };

  const toggleSort = (field) => {
    const dir = query.sort === field ? (query.dir === "asc" ? "desc" : "asc") : "asc";
    applyQuery({ sort: field, dir });
  };

  const openCreate = () => {
    setEditing(null);
    setForm({
      code: "",
      name: "",
      email: "",
      phone: "",
      address: "",
      country: "",
      city: "",
      timezone: "",
      currency_id: "",
      is_head_office: false,
      active: true,
    });
    setFormOpen(true);
  };

  const openEdit = (b) => {
    setEditing(b);
    setForm({
      code: b.code ?? "",
      name: b.name ?? "",
      email: b.email ?? "",
      phone: b.phone ?? "",
      address: b.address ?? "",
      country: b.country ?? "",
      city: b.city ?? "",
      timezone: b.timezone ?? "",
      currency_id: b.currency_id ?? "",
      is_head_office: !!b.is_head_office,
      active: !!b.active,
    });
    setFormOpen(true);
  };

  const submit = (e) => {
    e.preventDefault();

    if (editing) {
      router.put(`/branches/${editing.id}`, form, {
        onSuccess: () => setFormOpen(false),
      });
    } else {
      router.post("/branches", form, {
        onSuccess: () => setFormOpen(false),
      });
    }
  };

  const destroyOne = (b) => {
    if (!confirm(`Delete branch "${b.name}"?`)) return;
    router.delete(`/branches/${b.id}`);
  };

  const bulkDelete = () => {
    if (selectedIds.length === 0) return;
    if (!confirm(`Delete ${selectedIds.length} branches?`)) return;

    router.delete("/branches/bulk", {
      data: { ids: selectedIds },
      onSuccess: () => setSelectedIds([]),
    });
  };

  // Example bulk update: set active=true/false for selected
  const bulkSetActive = (active) => {
    if (selectedIds.length === 0) return;

    router.put("/branches/bulk", {
      items: selectedIds.map((id) => ({ id, active })),
    }, {
      onSuccess: () => setSelectedIds([]),
    });
  };

  // Example bulk create (simple demo): you’d normally paste/import rows
  const bulkCreateDemo = () => {
    router.post("/branches/bulk", {
      items: [
        { name: "Branch A", code: "A", active: true },
        { name: "Branch B", code: "B", active: true },
      ],
    });
  };

  const allRows = branches.data || [];

  const allSelected = selectedIds.length > 0 && selectedIds.length === allRows.length;

  const toggleSelectAll = () => {
    if (allSelected) setSelectedIds([]);
    else setSelectedIds(allRows.map((x) => x.id));
  };

  const toggleSelect = (id) => {
    setSelectedIds((prev) => (prev.includes(id) ? prev.filter((x) => x !== id) : [...prev, id]));
  };

  return (
    <div style={{ padding: 24 }}>
      <Head title="Branches" />

      <h2>Branches</h2>

      {flash?.success ? (
        <div style={{ background: "#e7ffe7", padding: 10, marginBottom: 12, borderRadius: 8 }}>
          {flash.success}
        </div>
      ) : null}

      {/* Filters */}
      <div style={{ display: "flex", gap: 8, flexWrap: "wrap", marginBottom: 12 }}>
        <input
          placeholder="Search..."
          value={query.q || ""}
          onChange={(e) => applyQuery({ q: e.target.value, page: 1 })}
          style={{ padding: 8, minWidth: 240 }}
        />

        <select
          value={query.active ?? ""}
          onChange={(e) => applyQuery({ active: e.target.value, page: 1 })}
          style={{ padding: 8 }}
        >
          <option value="">Active (all)</option>
          <option value="1">Active</option>
          <option value="0">Inactive</option>
        </select>

        <select
          value={query.is_head_office ?? ""}
          onChange={(e) => applyQuery({ is_head_office: e.target.value, page: 1 })}
          style={{ padding: 8 }}
        >
          <option value="">Head Office (all)</option>
          <option value="1">Head Office</option>
          <option value="0">Not Head Office</option>
        </select>

        <select
          value={query.country ?? ""}
          onChange={(e) => applyQuery({ country: e.target.value, page: 1 })}
          style={{ padding: 8 }}
        >
          <option value="">Country (all)</option>
          {(meta.countries || []).map((c) => (
            <option key={c} value={c}>{c}</option>
          ))}
        </select>

        <select
          value={query.city ?? ""}
          onChange={(e) => applyQuery({ city: e.target.value, page: 1 })}
          style={{ padding: 8 }}
        >
          <option value="">City (all)</option>
          {(meta.cities || []).map((c) => (
            <option key={c} value={c}>{c}</option>
          ))}
        </select>

        <select
          value={query.currency_id ?? ""}
          onChange={(e) => applyQuery({ currency_id: e.target.value, page: 1 })}
          style={{ padding: 8 }}
        >
          <option value="">Currency (all)</option>
          {(meta.currencies || []).map((c) => (
            <option key={c.id} value={c.id}>{c.code} - {c.name}</option>
          ))}
        </select>

        <button onClick={() => openCreate()} style={{ padding: "8px 12px" }}>
          + New
        </button>

        <button onClick={bulkDelete} disabled={selectedIds.length === 0} style={{ padding: "8px 12px" }}>
          Bulk Delete
        </button>

        <button onClick={() => bulkSetActive(true)} disabled={selectedIds.length === 0} style={{ padding: "8px 12px" }}>
          Set Active
        </button>

        <button onClick={() => bulkSetActive(false)} disabled={selectedIds.length === 0} style={{ padding: "8px 12px" }}>
          Set Inactive
        </button>

        <button onClick={bulkCreateDemo} style={{ padding: "8px 12px" }}>
          Bulk Create (demo)
        </button>
      </div>

      {/* Table */}
      <div style={{ overflowX: "auto" }}>
        <table width="100%" cellPadding="10" style={{ borderCollapse: "collapse" }}>
          <thead>
            <tr>
              <th style={{ borderBottom: "1px solid #ddd" }}>
                <input type="checkbox" checked={allSelected} onChange={toggleSelectAll} />
              </th>
              <th style={{ borderBottom: "1px solid #ddd", cursor: "pointer" }} onClick={() => toggleSort("name")}>
                Name {query.sort === "name" ? (query.dir === "asc" ? "↑" : "↓") : ""}
              </th>
              <th style={{ borderBottom: "1px solid #ddd", cursor: "pointer" }} onClick={() => toggleSort("code")}>
                Code {query.sort === "code" ? (query.dir === "asc" ? "↑" : "↓") : ""}
              </th>
              <th style={{ borderBottom: "1px solid #ddd", cursor: "pointer" }} onClick={() => toggleSort("country")}>
                Country {query.sort === "country" ? (query.dir === "asc" ? "↑" : "↓") : ""}
              </th>
              <th style={{ borderBottom: "1px solid #ddd", cursor: "pointer" }} onClick={() => toggleSort("city")}>
                City {query.sort === "city" ? (query.dir === "asc" ? "↑" : "↓") : ""}
              </th>
              <th style={{ borderBottom: "1px solid #ddd", cursor: "pointer" }} onClick={() => toggleSort("active")}>
                Active {query.sort === "active" ? (query.dir === "asc" ? "↑" : "↓") : ""}
              </th>
              <th style={{ borderBottom: "1px solid #ddd", cursor: "pointer" }} onClick={() => toggleSort("is_head_office")}>
                HO {query.sort === "is_head_office" ? (query.dir === "asc" ? "↑" : "↓") : ""}
              </th>
              <th style={{ borderBottom: "1px solid #ddd" }}>Actions</th>
            </tr>
          </thead>

          <tbody>
            {allRows.map((b) => (
              <tr key={b.id}>
                <td style={{ borderBottom: "1px solid #eee" }}>
                  <input type="checkbox" checked={selectedIds.includes(b.id)} onChange={() => toggleSelect(b.id)} />
                </td>
                <td style={{ borderBottom: "1px solid #eee" }}>{b.name}</td>
                <td style={{ borderBottom: "1px solid #eee" }}>{b.code || "-"}</td>
                <td style={{ borderBottom: "1px solid #eee" }}>{b.country || "-"}</td>
                <td style={{ borderBottom: "1px solid #eee" }}>{b.city || "-"}</td>
                <td style={{ borderBottom: "1px solid #eee" }}>{b.active ? "Yes" : "No"}</td>
                <td style={{ borderBottom: "1px solid #eee" }}>{b.is_head_office ? "Yes" : "No"}</td>
                <td style={{ borderBottom: "1px solid #eee" }}>
                  <button onClick={() => openEdit(b)} style={{ marginRight: 8 }}>Edit</button>
                  <button onClick={() => destroyOne(b)}>Delete</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* Pagination */}
      <div style={{ marginTop: 12, display: "flex", gap: 8, alignItems: "center", flexWrap: "wrap" }}>
        <span>
          Showing {branches.from ?? 0} - {branches.to ?? 0} of {branches.total ?? 0}
        </span>

        <div style={{ display: "flex", gap: 6 }}>
          {(branches.links || []).map((l, idx) => (
            <button
              key={idx}
              disabled={!l.url || l.active}
              onClick={() => l.url && router.get(l.url, {}, { preserveState: true, replace: true })}
              dangerouslySetInnerHTML={{ __html: l.label }}
            />
          ))}
        </div>
      </div>

      {/* Simple modal-like form */}
      {formOpen && (
        <div
          style={{
            position: "fixed",
            top: 0, left: 0, right: 0, bottom: 0,
            background: "rgba(0,0,0,0.35)",
            display: "flex",
            alignItems: "center",
            justifyContent: "center",
            padding: 20,
          }}
        >
          <div style={{ background: "#fff", borderRadius: 12, padding: 16, width: 720, maxWidth: "100%" }}>
            <h3 style={{ marginTop: 0 }}>{editing ? "Edit Branch" : "New Branch"}</h3>

            <form onSubmit={submit}>
              <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 10 }}>
                <input placeholder="Code" value={form.code} onChange={(e) => setForm({ ...form, code: e.target.value })} />
                <input placeholder="Name *" value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })} />

                <input placeholder="Email" value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })} />
                <input placeholder="Phone" value={form.phone} onChange={(e) => setForm({ ...form, phone: e.target.value })} />

                <input placeholder="Country" value={form.country} onChange={(e) => setForm({ ...form, country: e.target.value })} />
                <input placeholder="City" value={form.city} onChange={(e) => setForm({ ...form, city: e.target.value })} />

                <input placeholder="Timezone" value={form.timezone} onChange={(e) => setForm({ ...form, timezone: e.target.value })} />

                <select value={form.currency_id} onChange={(e) => setForm({ ...form, currency_id: e.target.value })}>
                  <option value="">Currency (optional)</option>
                  {(meta.currencies || []).map((c) => (
                    <option key={c.id} value={c.id}>{c.code} - {c.name}</option>
                  ))}
                </select>

                <input
                  placeholder="Address"
                  value={form.address}
                  onChange={(e) => setForm({ ...form, address: e.target.value })}
                  style={{ gridColumn: "1 / -1" }}
                />

                <label style={{ display: "flex", gap: 8, alignItems: "center" }}>
                  <input
                    type="checkbox"
                    checked={form.is_head_office}
                    onChange={(e) => setForm({ ...form, is_head_office: e.target.checked })}
                  />
                  Head Office
                </label>

                <label style={{ display: "flex", gap: 8, alignItems: "center" }}>
                  <input
                    type="checkbox"
                    checked={form.active}
                    onChange={(e) => setForm({ ...form, active: e.target.checked })}
                  />
                  Active
                </label>
              </div>

              <div style={{ display: "flex", justifyContent: "flex-end", gap: 8, marginTop: 12 }}>
                <button type="button" onClick={() => setFormOpen(false)}>Cancel</button>
                <button type="submit">{editing ? "Update" : "Create"}</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
