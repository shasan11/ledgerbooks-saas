import React, { useState } from "react";
import { Head, router, usePage } from "@inertiajs/react";

export default function ProductCategoriesIndex() {
  const { categories, parents, query, flash } = usePage().props;

  const [selectedIds, setSelectedIds] = useState([]);
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState(null);

  const [form, setForm] = useState({
    name: "",
    parent_id: "",
    description: "",
    active: true,
  });

  const rows = categories.data || [];
  const allSelected = selectedIds.length > 0 && selectedIds.length === rows.length;

  const applyQuery = (next) => {
    router.get("/product-categories", { ...query, ...next }, { preserveState: true, replace: true });
  };

  const toggleSort = (field) => {
    const dir = query.sort === field ? (query.dir === "asc" ? "desc" : "asc") : "asc";
    applyQuery({ sort: field, dir });
  };

  const openCreate = () => {
    setEditing(null);
    setForm({ name: "", parent_id: "", description: "", active: true });
    setOpen(true);
  };

  const openEdit = (c) => {
    setEditing(c);
    setForm({
      name: c.name ?? "",
      parent_id: c.parent_id ?? "",
      description: c.description ?? "",
      active: !!c.active,
    });
    setOpen(true);
  };

  const submit = (e) => {
    e.preventDefault();
    if (editing) {
      router.put(`/product-categories/${editing.id}`, form, { onSuccess: () => setOpen(false) });
    } else {
      router.post(`/product-categories`, form, { onSuccess: () => setOpen(false) });
    }
  };

  const destroyOne = (c) => {
    if (!confirm(`Delete category "${c.name}"?`)) return;
    router.delete(`/product-categories/${c.id}`);
  };

  const toggleSelectAll = () => {
    if (allSelected) setSelectedIds([]);
    else setSelectedIds(rows.map((r) => r.id));
  };

  const toggleSelect = (id) => {
    setSelectedIds((prev) => (prev.includes(id) ? prev.filter((x) => x !== id) : [...prev, id]));
  };

  const bulkDelete = () => {
    if (selectedIds.length === 0) return;
    if (!confirm(`Delete ${selectedIds.length} categories?`)) return;
    router.delete("/product-categories/bulk", {
      data: { ids: selectedIds },
      onSuccess: () => setSelectedIds([]),
    });
  };

  const bulkSetActive = (active) => {
    if (selectedIds.length === 0) return;
    router.put("/product-categories/bulk", {
      items: selectedIds.map((id) => ({ id, active })),
    }, {
      onSuccess: () => setSelectedIds([]),
    });
  };

  return (
    <div style={{ padding: 24 }}>
      <Head title="Product Categories" />
      <h2>Product Categories</h2>

      {flash?.success ? (
        <div style={{ background: "#e7ffe7", padding: 10, marginBottom: 12, borderRadius: 8 }}>
          {flash.success}
        </div>
      ) : null}

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
          value={query.parent_id ?? ""}
          onChange={(e) => applyQuery({ parent_id: e.target.value, page: 1 })}
          style={{ padding: 8 }}
        >
          <option value="">Parent (all)</option>
          {parents.map((p) => (
            <option key={p.id} value={p.id}>{p.name}</option>
          ))}
        </select>

        <button onClick={openCreate} style={{ padding: "8px 12px" }}>+ New</button>

        <button onClick={bulkDelete} disabled={selectedIds.length === 0} style={{ padding: "8px 12px" }}>
          Bulk Delete
        </button>
        <button onClick={() => bulkSetActive(true)} disabled={selectedIds.length === 0} style={{ padding: "8px 12px" }}>
          Set Active
        </button>
        <button onClick={() => bulkSetActive(false)} disabled={selectedIds.length === 0} style={{ padding: "8px 12px" }}>
          Set Inactive
        </button>
      </div>

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
              <th style={{ borderBottom: "1px solid #ddd" }}>Parent</th>
              <th style={{ borderBottom: "1px solid #ddd", cursor: "pointer" }} onClick={() => toggleSort("active")}>
                Active {query.sort === "active" ? (query.dir === "asc" ? "↑" : "↓") : ""}
              </th>
              <th style={{ borderBottom: "1px solid #ddd" }}>Actions</th>
            </tr>
          </thead>

          <tbody>
            {rows.map((c) => (
              <tr key={c.id}>
                <td style={{ borderBottom: "1px solid #eee" }}>
                  <input type="checkbox" checked={selectedIds.includes(c.id)} onChange={() => toggleSelect(c.id)} />
                </td>
                <td style={{ borderBottom: "1px solid #eee" }}>{c.name}</td>
                <td style={{ borderBottom: "1px solid #eee" }}>
                  {c.parent_id ? (parents.find(p => p.id === c.parent_id)?.name || "—") : "—"}
                </td>
                <td style={{ borderBottom: "1px solid #eee" }}>{c.active ? "Yes" : "No"}</td>
                <td style={{ borderBottom: "1px solid #eee" }}>
                  <button onClick={() => openEdit(c)} style={{ marginRight: 8 }}>Edit</button>
                  <button onClick={() => destroyOne(c)}>Delete</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      <div style={{ marginTop: 12, display: "flex", gap: 8, alignItems: "center", flexWrap: "wrap" }}>
        <span>
          Showing {categories.from ?? 0} - {categories.to ?? 0} of {categories.total ?? 0}
        </span>

        <div style={{ display: "flex", gap: 6 }}>
          {(categories.links || []).map((l, idx) => (
            <button
              key={idx}
              disabled={!l.url || l.active}
              onClick={() => l.url && router.get(l.url, {}, { preserveState: true, replace: true })}
              dangerouslySetInnerHTML={{ __html: l.label }}
            />
          ))}
        </div>
      </div>

      {open && (
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
            <h3 style={{ marginTop: 0 }}>{editing ? "Edit Category" : "New Category"}</h3>

            <form onSubmit={submit}>
              <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 10 }}>
                <input
                  placeholder="Name *"
                  value={form.name}
                  onChange={(e) => setForm({ ...form, name: e.target.value })}
                />

                <select
                  value={form.parent_id || ""}
                  onChange={(e) => setForm({ ...form, parent_id: e.target.value || "" })}
                >
                  <option value="">Parent (optional)</option>
                  {parents
                    .filter(p => !editing || p.id !== editing.id) // prevent self-parent UI-side
                    .map((p) => (
                      <option key={p.id} value={p.id}>{p.name}</option>
                    ))}
                </select>

                <textarea
                  placeholder="Description"
                  value={form.description}
                  onChange={(e) => setForm({ ...form, description: e.target.value })}
                  style={{ gridColumn: "1 / -1", minHeight: 90 }}
                />

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
                <button type="button" onClick={() => setOpen(false)}>Cancel</button>
                <button type="submit">{editing ? "Update" : "Create"}</button>
              </div>
            </form>
          </div>
        </div>
      )}

    </div>
  );
}
