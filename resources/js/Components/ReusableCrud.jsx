import React, { useEffect, useMemo, useState } from "react";
import { router, usePage } from "@inertiajs/react";
import {
  Table,
  Modal,
  Button,
  Form,
  Input,
  Switch,
  Upload,
  Radio,
  message,
  Row,
  Col,
  Select,
  InputNumber,
  Transfer,
  DatePicker,
  Drawer,
  Checkbox,
  Collapse,
  Dropdown,
} from "antd";
import {
  PlusOutlined,
  UploadOutlined,
  DownloadOutlined,
  EditOutlined,
  DeleteOutlined,
  EyeInvisibleOutlined,
  MoreOutlined,
  ReloadOutlined,
  EllipsisOutlined,
} from "@ant-design/icons";
import { Formik, Form as FormikForm, Field, FieldArray } from "formik";
import * as XLSX from "xlsx";
import { saveAs } from "file-saver";
import moment from "moment";

const { Panel } = Collapse;

/* ----------------------------- helpers ----------------------------- */
const isFileLike = (v) => typeof File !== "undefined" && v instanceof File;

const hasAnyFile = (obj) => {
  const walk = (x) => {
    if (isFileLike(x)) return true;
    if (Array.isArray(x)) return x.some(walk);
    if (x && typeof x === "object") return Object.values(x).some(walk);
    return false;
  };
  return walk(obj);
};

const buildFormData = (values) => {
  const fd = new FormData();
  Object.entries(values || {}).forEach(([k, v]) => {
    if (v === undefined || v === null) return;

    if (
      Array.isArray(v) ||
      (typeof v === "object" && !isFileLike(v) && !moment.isMoment(v))
    ) {
      fd.append(k, JSON.stringify(v));
      return;
    }

    fd.append(k, v);
  });
  return fd;
};

const normalizeOption = (opt) => ({
  id: opt?.id ?? opt?.value,
  value: opt?.value ?? opt?.id,
  label: opt?.label ?? opt?.name ?? String(opt?.value ?? opt?.id ?? ""),
});

const safeHashGet = () => {
  if (typeof window === "undefined") return "";
  return (window.location.hash || "").replace("#", "").trim();
};

const safeHashSet = (key) => {
  if (typeof window === "undefined") return;
  const next = `#${key}`;
  if (window.location.hash !== next) window.location.hash = next;
};

const stripEmpty = (obj) => {
  const out = { ...(obj || {}) };
  Object.keys(out).forEach((k) => {
    const v = out[k];
    if (v === "" || v === null || v === undefined) delete out[k];
  });
  return out;
};

const toInt = (v, fallback) => {
  const n = Number(v);
  return Number.isFinite(n) ? n : fallback;
};

/* ----------------------------- Anchor filter tabs UI ----------------------------- */
function AnchorFilterTabs({ items = [], activeKey, onChange, leftTitle, rightNode }) {
  const activeItem = items.find((x) => x.key === activeKey);

  return (
    <div
      style={{
        display: "flex",
        alignItems: "center",
        gap: 18,
        padding: "12px 16px",
        borderBottom: "1px solid #eef0f4",
        background: "#fff",
      }}
    >
      <div style={{ fontSize: 22, fontWeight: 800, color: "#0f172a" }}>
        {activeItem?.title || leftTitle || ""}
      </div>

      <div style={{ flex: 1, display: "flex", alignItems: "center", gap: 26, marginLeft: 24 }}>
        {items.map((it) => {
          const isActive = it.key === activeKey;
          return (
            <button
              key={it.key}
              type="button"
              onClick={() => onChange?.(it.key)}
              style={{
                appearance: "none",
                background: "transparent",
                border: "none",
                padding: "12px 4px",
                cursor: "pointer",
                fontSize: 16,
                fontWeight: isActive ? 800 : 500,
                color: isActive ? "#0f172a" : "#64748b",
                position: "relative",
              }}
            >
              {it.label}
              {isActive && (
                <span
                  style={{
                    position: "absolute",
                    left: 0,
                    right: 0,
                    bottom: -13,
                    height: 4,
                    background: "#2563eb",
                    borderRadius: 2,
                  }}
                />
              )}
            </button>
          );
        })}
      </div>

      {rightNode ? (
        <div style={{ display: "flex", alignItems: "center", gap: 10 }}>{rightNode}</div>
      ) : null}
    </div>
  );
}

/* ----------------------------- inner form wrapper ----------------------------- */
function CrudFormInner({
  fields,
  values,
  setFieldValue,
  errors,
  touched,
  handleSubmit,
  onFormValuesChange,
  submitLabel = "Save",
  renderFormFields,
  hideSubmitButton = false,
}) {
  const walkFields = (fs, cb) => {
    fs.forEach((f) => {
      if (f.type === "group" && Array.isArray(f.children)) walkFields(f.children, cb);
      else cb(f);
    });
  };

  useEffect(() => {
    if (typeof onFormValuesChange === "function") onFormValuesChange(values);
  }, [values, onFormValuesChange]);

  useEffect(() => {
    walkFields(fields, (field) => {
      if (typeof field.formula === "function" && field.name) {
        const newVal = field.formula(values);
        const currentVal = values?.[field.name];
        if (newVal !== currentVal) setFieldValue(field.name, newVal, false);
      }
    });
  }, [values, fields, setFieldValue]);

  return (
    <FormikForm onSubmit={handleSubmit}>
      {renderFormFields(values, setFieldValue, errors, touched)}
      {!hideSubmitButton && (
        <Button type="primary" htmlType="submit">
          {submitLabel}
        </Button>
      )}
    </FormikForm>
  );
}

/* ----------------------------- main component ----------------------------- */
/**
 * IMPORTANT:
 * - You DO NOT pass apiUrl (like DRF). You pass:
 *   - indexUrl: where the list comes from (controller returns Inertia props)
 *   - storeUrl, updateUrl(id), destroyUrl(id)
 *   - bulkUrl (optional) for bulk ops
 * - Props from controller:
 *   - items: paginator object for ACTIVE view
 *   - inactiveItems: paginator object for INACTIVE view (optional)
 *   - query: current query (page, per_page, search, etc.)
 */
export default function ReusableCrudInertia({
  // urls
  indexUrl,
  storeUrl,
  updateUrl, // (id) => url
  destroyUrl, // (id) => url
  bulkUrl = null, // e.g. "/product-categories/bulk"

  title,
  fields,
  columns,
  validationSchema,

  // same props as your DRF component (kept)
  bulkactions = [],
  singleactions = [],
  ui_type,
  modalStyle,
  handleAddedData,
  rowMenu,
  crudInitialValues = {},
  showSearch = true,
  modalWidth = 700,
  hasActions = true,
  showRowActionMenu = true,

  // permissions
  canView = true,
  canEdit = true,
  canAdd = true,
  canDelete = true,

  // anchor tabs
  anchorFilters = [],
  defaultAnchorKey = null,
  anchorSyncWithHash = true,
  anchorParamResolver = null,
  onAnchorChange = null,

  // query keys
  pageParam = "page",
  pageSizeParam = "per_page",
  searchParam = "search",
  activeParam = "active", // server expects active=1/0

  // inactive drawer
  enableInactiveDrawer = true,

  // form UI
  form_ui = "modal",
  drawerWidth = 1200,

  // server sorting
  sortParam = "sort",
  dirParam = "dir",
}){
  const { props } = usePage();
  const items = props.items; // paginator for active list
  const inactiveItems = props.inactiveItems; // paginator for inactive drawer (optional)
  const query = props.query || {};

  const [visible, setVisible] = useState(false);
  const [inactiveDrawer, setInactiveDrawer] = useState(false);
  const [editingRecord, setEditingRecord] = useState(null);
  const [selectedRowKeys, setSelectedRowKeys] = useState([]);

  const [searchText, setSearchText] = useState(query?.[searchParam] ?? "");
  const [inactiveSearchText, setInactiveSearchText] = useState("");

  // Anchor state
  const hasAnchors = Array.isArray(anchorFilters) && anchorFilters.length > 0;

  const initialAnchorKey = useMemo(() => {
    const first = anchorFilters?.[0]?.key;
    const desiredDefault = defaultAnchorKey || first;

    if (!anchorSyncWithHash) return desiredDefault;

    const fromHash = safeHashGet();
    const exists = anchorFilters?.some((x) => x.key === fromHash);
    return exists ? fromHash : desiredDefault;
  }, [anchorFilters, defaultAnchorKey, anchorSyncWithHash]);

  const [activeAnchorKey, setActiveAnchorKey] = useState(initialAnchorKey);

  useEffect(() => {
    if (!anchorSyncWithHash || typeof window === "undefined") return;
    const onHash = () => {
      const h = safeHashGet();
      if (!h) return;
      const exists = anchorFilters?.some((x) => x.key === h);
      if (exists) setActiveAnchorKey(h);
    };
    window.addEventListener("hashchange", onHash);
    return () => window.removeEventListener("hashchange", onHash);
  }, [anchorSyncWithHash, anchorFilters]);

  const activeAnchorItem = useMemo(
    () => anchorFilters.find((x) => x.key === activeAnchorKey),
    [anchorFilters, activeAnchorKey]
  );

  const anchorParams = useMemo(() => {
    if (!hasAnchors) return {};
    if (typeof anchorParamResolver === "function") {
      return anchorParamResolver(activeAnchorKey, activeAnchorItem) || {};
    }
    return activeAnchorItem?.params || {};
  }, [hasAnchors, anchorParamResolver, activeAnchorKey, activeAnchorItem]);

  const currentRows = items?.data || [];
  const inactiveRows = inactiveItems?.data || [];

  // Use server paginated always (paginator)
  const pagination = useMemo(() => ({
    current: toInt(items?.current_page, 1),
    pageSize: toInt(items?.per_page, 20),
    total: toInt(items?.total, 0),
  }), [items]);

  const inactivePagination = useMemo(() => ({
    current: toInt(inactiveItems?.current_page, 1),
    pageSize: toInt(inactiveItems?.per_page, 10),
    total: toInt(inactiveItems?.total, 0),
  }), [inactiveItems]);

  const visit = (nextQuery) => {
    router.get(indexUrl, stripEmpty(nextQuery), {
      preserveState: true,
      replace: true,
      preserveScroll: true,
    });
  };

  const refetchActive = (overrides = {}) => {
    visit({
      ...query,
      ...anchorParams,
      ...overrides,
      [activeParam]: 1,
    });
  };

  const refetchInactive = (overrides = {}) => {
    // Same index route, but request inactive list props
    visit({
      ...query,
      ...anchorParams,
      ...overrides,
      inactive_drawer: 1, // tells controller to also return inactiveItems
      [activeParam]: 1, // keep active list stable
      inactive_search: overrides.inactive_search ?? inactiveSearchText,
      inactive_page: overrides.inactive_page ?? inactivePagination.current,
      inactive_per_page: overrides.inactive_per_page ?? inactivePagination.pageSize,
    });
  };

  // anchor change => reset pages
  const changeAnchor = (key) => {
    setActiveAnchorKey(key);
    if (anchorSyncWithHash) safeHashSet(key);
    setSelectedRowKeys([]);

    if (typeof onAnchorChange === "function") onAnchorChange(key);

    visit({
      ...query,
      ...((anchorFilters.find((x) => x.key === key)?.params) || {}),
      [pageParam]: 1,
      [activeParam]: 1,
    });
  };

  /* ----------------------------- export/import ----------------------------- */
  const handleExport = () => {
    const ws = XLSX.utils.json_to_sheet(currentRows || []);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, title || "Export");
    const wbout = XLSX.write(wb, { bookType: "xlsx", type: "binary" });

    const buf = new ArrayBuffer(wbout.length);
    const view = new Uint8Array(buf);
    for (let i = 0; i < wbout.length; ++i) view[i] = wbout.charCodeAt(i) & 0xff;

    saveAs(new Blob([buf], { type: "application/octet-stream" }), `${title || "export"}.xlsx`);
  };

  const handleImport = (file) => {
    if (!bulkUrl) {
      message.error("bulkUrl missing (need endpoint for import/bulk create)");
      return false;
    }

    const reader = new FileReader();
    reader.onload = async (e) => {
      try {
        const wb = XLSX.read(e.target.result, { type: "binary" });
        const sheet = wb.Sheets[wb.SheetNames[0]];
        const json = XLSX.utils.sheet_to_json(sheet);

        router.post(
          bulkUrl,
          { op: "import", rows: json },
          {
            preserveScroll: true,
            onSuccess: () => {
              message.success("Import complete");
              refetchActive();
            },
            onError: () => message.error("Import failed"),
          }
        );
      } catch (err) {
        console.error(err);
        message.error("Import failed");
      }
    };
    reader.readAsBinaryString(file);
    return false;
  };

  /* ----------------------------- server mutations ----------------------------- */
  const softDelete = (record) => {
    if (!canDelete) return;
    router.put(
      updateUrl(record.id),
      { ...record, active: false },
      {
        preserveScroll: true,
        onSuccess: () => {
          message.success("Marked inactive");
          refetchActive({ [pageParam]: pagination.current });
          if (enableInactiveDrawer && inactiveDrawer) refetchInactive();
        },
        onError: () => message.error("Failed"),
      }
    );
  };

  const activate = (record) => {
    if (!canDelete) return;
    router.put(
      updateUrl(record.id),
      { ...record, active: true },
      {
        preserveScroll: true,
        onSuccess: () => {
          message.success("Activated");
          refetchActive({ [pageParam]: pagination.current });
          if (enableInactiveDrawer && inactiveDrawer) refetchInactive();
        },
        onError: () => message.error("Failed"),
      }
    );
  };

  const deletePermanent = (record) => {
    if (!canDelete) return;
    router.delete(destroyUrl(record.id), {
      preserveScroll: true,
      onSuccess: () => {
        message.success("Deleted");
        refetchActive({ [pageParam]: pagination.current });
        if (enableInactiveDrawer && inactiveDrawer) refetchInactive();
      },
      onError: () => message.error("Failed"),
    });
  };

  const bulkInactivate = () => {
    if (!bulkUrl) return message.error("bulkUrl missing");
    if (!selectedRowKeys.length) return;

    router.post(
      bulkUrl,
      { op: "inactivate", ids: selectedRowKeys },
      {
        preserveScroll: true,
        onSuccess: () => {
          message.success("Selected records marked inactive");
          setSelectedRowKeys([]);
          refetchActive({ [pageParam]: 1 });
        },
        onError: () => message.error("Bulk failed"),
      }
    );
  };

  const bulkActivate = () => {
    if (!bulkUrl) return message.error("bulkUrl missing");
    if (!selectedRowKeys.length) return;

    router.post(
      bulkUrl,
      { op: "activate", ids: selectedRowKeys },
      {
        preserveScroll: true,
        onSuccess: () => {
          message.success("Selected records marked active");
          setSelectedRowKeys([]);
          refetchActive({ [pageParam]: 1 });
          if (enableInactiveDrawer && inactiveDrawer) refetchInactive();
        },
        onError: () => message.error("Bulk failed"),
      }
    );
  };

  /* ----------------------------- menus ----------------------------- */
  const getRowActionItems = (record, isInactive = false) => {
    const itemsArr = [];

    if (canEdit) {
      itemsArr.push({
        key: "edit",
        icon: <EditOutlined />,
        label: "Edit",
        onClick: () => {
          setEditingRecord(record);
          setVisible(true);
        },
      });
    }

    (singleactions || []).forEach((action, index) => {
      itemsArr.push({
        key: `custom-${index}`,
        icon: action?.icon || <ReloadOutlined />,
        label: action.label,
        onClick: () => {
          router.put(
            updateUrl(record.id),
            { ...record, ...(action.actions || {}) },
            {
              preserveScroll: true,
              onSuccess: () => {
                message.success(`${action.label} successful`);
                refetchActive({ [pageParam]: pagination.current });
              },
              onError: () => message.error("Action failed"),
            }
          );
        },
      });
    });

    if (isInactive) {
      if (canDelete) {
        itemsArr.push({
          key: "activate",
          icon: <ReloadOutlined style={{ color: "green" }} />,
          label: "Activate",
          onClick: () => activate(record),
        });
        itemsArr.push({
          key: "delete",
          icon: <DeleteOutlined style={{ color: "red" }} />,
          label: "Delete Permanently",
          danger: true,
          onClick: () => deletePermanent(record),
        });
      }
    } else {
      if (canDelete) {
        itemsArr.push({
          key: "inactivate",
          icon: <DeleteOutlined style={{ color: "red" }} />,
          label: "Inactivate",
          danger: true,
          onClick: () => softDelete(record),
        });
      }
    }

    if (!itemsArr.length) itemsArr.push({ key: "noop", label: "No actions", disabled: true });
    return itemsArr;
  };

  const topMenuItems = useMemo(() => {
    const arr = [];

    if (canView) {
      arr.push(
        { key: "export", icon: <DownloadOutlined />, label: "Export (this page)", onClick: handleExport },
        {
          key: "import",
          icon: <UploadOutlined />,
          label: (
            <Upload beforeUpload={handleImport} showUploadList={false}>
              <span>Import</span>
            </Upload>
          ),
        }
      );

      if (enableInactiveDrawer) {
        arr.push({
          key: "inactive",
          icon: <EyeInvisibleOutlined />,
          label: `View Inactive (${inactivePagination.total})`,
          onClick: () => {
            const next = !inactiveDrawer;
            setInactiveDrawer(next);
            if (next) {
              // tell server to include inactiveItems
              refetchInactive({ inactive_drawer: 1, inactive_page: 1 });
            }
          },
        });
      }
    }

    if (canDelete) {
      arr.push(
        {
          key: "bulk-inactivate",
          icon: <DeleteOutlined style={{ color: "red" }} />,
          label: `Inactivate Selected (${selectedRowKeys.length})`,
          danger: true,
          disabled: !selectedRowKeys.length,
          onClick: bulkInactivate,
        },
        {
          key: "bulk-activate",
          icon: <ReloadOutlined style={{ color: "green" }} />,
          label: `Activate Selected (${selectedRowKeys.length})`,
          disabled: !selectedRowKeys.length,
          onClick: bulkActivate,
        }
      );
    }

    // custom menu items (your rowMenu concept)
    if (Array.isArray(rowMenu) && rowMenu.length) {
      rowMenu
        .filter((item) => {
          if (typeof item?.show === "function") return item.show({ selectedRowKeys, data: currentRows });
          return item?.show !== false;
        })
        .forEach((item, idx) => {
          if (item?.type === "divider") {
            arr.push({ type: "divider" });
            return;
          }

          const computedDisabled =
            typeof item?.disabled === "function"
              ? item.disabled({ selectedRowKeys, data: currentRows })
              : !!item?.disabled;

          const disabledBySelection = item?.requiresSelection && selectedRowKeys.length === 0;

          arr.push({
            key: `rm-${idx}`,
            icon: item?.icon,
            label: item?.label,
            danger: !!item?.danger,
            disabled: computedDisabled || disabledBySelection,
            onClick: () =>
              item?.onClick?.({
                selectedRowKeys,
                data: currentRows,
                refresh: () => refetchActive({ [pageParam]: pagination.current }),
                clearSelection: () => setSelectedRowKeys([]),
                message,
                router,
              }),
          });
        });
    }

    if (!arr.length) arr.push({ key: "noop", label: "No actions", disabled: true });
    return arr;
  }, [
    canView,
    canDelete,
    enableInactiveDrawer,
    inactivePagination.total,
    selectedRowKeys,
    rowMenu,
    currentRows,
    pagination.current,
    inactiveDrawer,
  ]);

  /* ----------------------------- columns ----------------------------- */
  const canRowActionsExist =
    showRowActionMenu && (canEdit || canDelete || (singleactions && singleactions.length > 0));

  const mainColumns = canRowActionsExist
    ? [
        ...columns,
        {
          title: "Actions",
          align: "right",
          width: 90,
          render: (_, record) => (
            <Dropdown menu={{ items: getRowActionItems(record, false) }} trigger={["click"]}>
              <Button shape="circle" icon={<EllipsisOutlined />} />
            </Dropdown>
          ),
        },
      ]
    : columns;

  const inactiveColumns = canRowActionsExist
    ? [
        ...columns,
        {
          title: "Actions",
          align: "right",
          width: 90,
          render: (_, record) => (
            <Dropdown menu={{ items: getRowActionItems(record, true) }} trigger={["click"]}>
              <Button shape="circle" icon={<EllipsisOutlined />} />
            </Dropdown>
          ),
        },
      ]
    : columns;

  /* ----------------------------- field renderer (same as your DRF version) ----------------------------- */
  const renderFormFields = (values, setFieldValue, errors, touched) => {
    const renderOneField = (field) => {
      if (field.condition && !field.condition(values)) return null;

      const colSpan = field.col ?? 24;
      const readOnly = !!field.readOnly;

      const label = field.label;
      const name = field.name;

      const setSelectWithLabel = (val, option) => {
        if (field.labelField) {
          const opt = Array.isArray(option) ? option?.[0] : option;
          const lbl = opt?.label ?? opt?.children ?? opt?.title ?? "";
          setFieldValue(field.labelField, lbl);
        }
        setFieldValue(name, val);
      };

      return (
        <Col xs={colSpan} className="px-2" key={name || label || Math.random()}>
          <Form.Item
            layout="vertical"
            label={label}
            size="large"
            required={field.required}
            validateStatus={touched?.[name] && errors?.[name] ? "error" : ""}
            help={touched?.[name] && errors?.[name]}
          >
            {(() => {
              switch (field.type) {
                case "textarea":
                  return (
                    <Field
                      name={name}
                      style={{ height: 37, minHeight: 40, padding: "4px 8px", lineHeight: "20px" }}
                      as={Input.TextArea}
                      size="large"
                      rows={field.rows || 1}
                      placeholder={field.placeholder || ""}
                      disabled={readOnly}
                    />
                  );

                case "number":
                  return (
                    <InputNumber
                      style={{ width: "100%" }}
                      value={values?.[name]}
                      min={field.min}
                      max={field.max}
                      size="large"
                      disabled={readOnly}
                      onChange={(val) => setFieldValue(name, val)}
                      placeholder={field.placeholder || ""}
                    />
                  );

                case "select": {
                  const options = (field.options || []).map(normalizeOption);
                  const currentVal = values?.[name];
                  const finalVal =
                    currentVal !== undefined && currentVal !== ""
                      ? currentVal
                      : field.defaultValue !== undefined && field.defaultValue !== ""
                      ? field.defaultValue
                      : undefined;

                  return (
                    <Select
                      showSearch
                      value={finalVal}
                      size="large"
                      disabled={readOnly}
                      placeholder={field.placeholder || "Select..."}
                      onChange={(val, option) => setSelectWithLabel(val, option)}
                      filterOption={(input, opt) =>
                        String(opt?.children ?? "").toLowerCase().includes(input.toLowerCase())
                      }
                    >
                      {options.map((opt) => (
                        <Select.Option key={opt.value} value={opt.value}>
                          {opt.label}
                        </Select.Option>
                      ))}
                    </Select>
                  );
                }

                case "checkbox":
                  return (
                    <Checkbox
                      checked={!!values?.[name]}
                      disabled={readOnly}
                      onChange={(e) => setFieldValue(name, e.target.checked)}
                    >
                      {field.inlineLabel || field.label}
                    </Checkbox>
                  );

                case "radio":
                  return (
                    <Radio.Group
                      block
                      size="large"
                      optionType="button"
                      buttonStyle="solid"
                      disabled={readOnly}
                      onChange={(e) => setFieldValue(name, e.target.value)}
                      value={values?.[name]}
                    >
                      {field.options?.map((opt) => (
                        <Radio key={opt.value} value={opt.value}>
                          {opt.label}
                        </Radio>
                      ))}
                    </Radio.Group>
                  );

                case "date":
                  return (
                    <DatePicker
                      size="large"
                      style={{ width: "100%" }}
                      disabled={readOnly}
                      value={values?.[name] ? moment(values[name]) : null}
                      onChange={(_, dateString) => setFieldValue(name, dateString)}
                      placeholder={field.placeholder || "Select date"}
                    />
                  );

                case "switch":
                  return (
                    <Switch
                      checked={!!values?.[name]}
                      disabled={readOnly}
                      onChange={(val) => setFieldValue(name, val)}
                    />
                  );

                case "file":
                  return (
                    <Upload
                      beforeUpload={(file) => {
                        setFieldValue(name, file);
                        return false;
                      }}
                      showUploadList={{ showPreviewIcon: true, showRemoveIcon: true }}
                      maxCount={field.maxCount || 1}
                    >
                      <Button size="large" icon={<UploadOutlined />}>
                        {field.buttonLabel || "Upload File"}
                      </Button>
                    </Upload>
                  );

                case "fieldArray":
                  return (
                    <FieldArray name={name}>
                      {({ push, remove }) => (
                        <div>
                          {(values?.[name] || []).map((item, index) => (
                            <div key={index} style={{ display: "flex", gap: 8, marginBottom: 8 }}>
                              <Input
                                size="large"
                                value={item}
                                disabled={readOnly}
                                onChange={(e) => setFieldValue(`${name}[${index}]`, e.target.value)}
                                placeholder={field.itemPlaceholder || field.placeholder || ""}
                              />
                              {!readOnly && (
                                <Button size="large" type="link" danger onClick={() => remove(index)}>
                                  Remove
                                </Button>
                              )}
                            </div>
                          ))}
                          {!readOnly && (
                            <Button type="dashed" icon={<PlusOutlined />} onClick={() => push(field.defaultItem || "")}>
                              {field.addButtonLabel || "Add item"}
                            </Button>
                          )}
                        </div>
                      )}
                    </FieldArray>
                  );

                // (You can paste your objectArray / transfer / group sections here if needed)
                default:
                  return (
                    <Field
                      name={name}
                      as={Input}
                      size="large"
                      disabled={readOnly}
                      placeholder={field.placeholder || ""}
                    />
                  );
              }
            })()}
          </Form.Item>
        </Col>
      );
    };

    return (
      <Row>
        {(fields || []).map((field) => {
          if (field.type === "group") {
            if (field.condition && !field.condition(values)) return null;

            const children = field.children || [];
            const key = field.name || field.label || Math.random().toString();
            const defaultActive = field.defaultOpen === undefined ? true : !!field.defaultOpen;

            return (
              <Col xs={field.col ?? 24} key={key} className={field.col < 24 ? "p-2" : "p-0"}>
                {field.accordion !== false ? (
                  <Collapse bordered={field.bordered ?? false} defaultActiveKey={defaultActive ? [key] : []}>
                    <Panel header={field.label} key={key}>
                      <Row>{children.map((child) => renderOneField(child))}</Row>
                    </Panel>
                  </Collapse>
                ) : (
                  <div
                    style={{
                      marginBottom: 16,
                      padding: 12,
                      borderRadius: 8,
                      border: "1px solid #f0f0f0",
                      background: "#fafafa",
                    }}
                  >
                    {field.label && <div style={{ fontWeight: 700, marginBottom: 8 }}>{field.label}</div>}
                    <Row>{children.map((child) => renderOneField(child))}</Row>
                  </div>
                )}
              </Col>
            );
          }

          return renderOneField(field);
        })}
      </Row>
    );
  };

  /* ----------------------------- submit (Inertia post/put) ----------------------------- */
  const submitRecord = (values, isEditMode, editId) => {
    const containsFile = hasAnyFile(values);

    if (!isEditMode) {
      router.post(
        storeUrl,
        containsFile ? buildFormData(values) : values,
        {
          forceFormData: containsFile,
          preserveScroll: true,
          onSuccess: () => {
            message.success("Saved successfully");
            setVisible(false);
            if (typeof handleAddedData === "function") handleAddedData(values);
            refetchActive({ [pageParam]: pagination.current });
          },
          onError: () => message.error("Something went wrong"),
        }
      );
      return;
    }

    router.put(
      updateUrl(editId),
      containsFile ? buildFormData(values) : values,
      {
        forceFormData: containsFile,
        preserveScroll: true,
        onSuccess: () => {
          message.success("Saved successfully");
          setVisible(false);
          refetchActive({ [pageParam]: pagination.current });
          if (enableInactiveDrawer && inactiveDrawer) refetchInactive();
        },
        onError: () => message.error("Something went wrong"),
      }
    );
  };

  /* ----------------------------- modal/drawer form ----------------------------- */
  const formTitle = editingRecord ? `Edit ${title?.slice?.(0, -1) || title}` : `Add ${title}`;

  const formNode = (
    <Formik
      enableReinitialize
      initialValues={editingRecord || crudInitialValues}
      validationSchema={validationSchema}
      onSubmit={async (values) => {
        const isEditMode = !!editingRecord;
        const id = editingRecord?.id;
        submitRecord(values, isEditMode, id);
      }}
    >
      {({ handleSubmit, submitForm, setFieldValue, errors, touched, values, isValid, isSubmitting }) => {
        const inner = (
          <CrudFormInner
            fields={fields}
            values={values}
            setFieldValue={setFieldValue}
            errors={errors}
            touched={touched}
            handleSubmit={handleSubmit}
            submitLabel={editingRecord ? "Update" : "Add"}
            renderFormFields={renderFormFields}
            hideSubmitButton={form_ui === "drawer"}
          />
        );

        if (form_ui === "drawer") {
          return (
            <Drawer
              width={drawerWidth}
              title={formTitle}
              open={visible}
              onClose={() => setVisible(false)}
              destroyOnClose
              extra={
                <Button type="primary" onClick={submitForm} disabled={!isValid || isSubmitting} loading={isSubmitting}>
                  Save
                </Button>
              }
            >
              {inner}
            </Drawer>
          );
        }

        return (
          <Modal
            style={modalStyle}
            title={formTitle}
            open={visible}
            width={modalWidth}
            onCancel={() => setVisible(false)}
            footer={null}
            destroyOnClose
          >
            {inner}
          </Modal>
        );
      }}
    </Formik>
  );

  /* ----------------------------- header right ----------------------------- */
  const headerRight = (
    <>
      {canAdd && (
        <Button
          icon={<PlusOutlined />}
          type="primary"
          style={{ borderRadius: 2, fontWeight: 800 }}
          onClick={() => {
            setEditingRecord(null);
            setVisible(true);
          }}
        >
          ADD NEW
        </Button>
      )}

      {hasActions && canView && (
        <Dropdown menu={{ items: topMenuItems }} placement="bottomLeft" trigger={["click"]}>
          <Button icon={<MoreOutlined />}>Actions</Button>
        </Dropdown>
      )}
    </>
  );

  /* ----------------------------- render ----------------------------- */
  if (!canView) {
    return <div style={{ padding: "12px 16px", color: "#999" }}>You do not have permission to view this list.</div>;
  }

  return (
    <>
      {hasAnchors && (
        <AnchorFilterTabs
          items={anchorFilters}
          activeKey={activeAnchorKey}
          onChange={(k) => changeAnchor(k)}
          leftTitle={title}
          rightNode={headerRight}
        />
      )}

      <div style={{ background: "#fff" }}>
        <div className="pt-3 px-3">
          {/* bulk actions bar */}
          {canView && selectedRowKeys.length > 0 && bulkactions?.length > 0 && (
            <div
              style={{
                marginBottom: 12,
                padding: "8px 12px",
                background: "#f6ffed",
                border: "1px solid #b7eb8f",
                borderRadius: 4,
              }}
            >
              <span style={{ marginRight: 12, fontWeight: 700 }}>
                Bulk Actions ({selectedRowKeys.length} selected):
              </span>
              {bulkactions.map((action, index) => (
                <Button
                  key={index}
                  type="text"
                  size="small"
                  onClick={() => {
                    if (!bulkUrl) return message.error("bulkUrl missing");
                    router.post(
                      bulkUrl,
                      { op: "update", ids: selectedRowKeys, actions: action.actions },
                      {
                        preserveScroll: true,
                        onSuccess: () => {
                          message.success(`${action.label} applied to ${selectedRowKeys.length} items`);
                          setSelectedRowKeys([]);
                          refetchActive({ [pageParam]: 1 });
                        },
                        onError: () => message.error("Bulk action failed"),
                      }
                    );
                  }}
                  style={{ marginRight: 8 }}
                >
                  {action.label}
                </Button>
              ))}
            </div>
          )}

          {/* search + actions */}
          <div className="flex gap-2 mb-4">
            <Row justify="space-between" style={{ width: "100%" }}>
              {showSearch && (
                <Col xs={16} style={{ display: "flex", gap: 6 }}>
                  <Input
                    size="large"
                    placeholder={`Search ${title}`}
                    allowClear
                    value={searchText}
                    onChange={(e) => {
                      const v = e.target.value;
                      setSearchText(v);
                      refetchActive({ [pageParam]: 1, [searchParam]: v });
                    }}
                    style={{ width: "100%", borderRadius: 0 }}
                  />
                </Col>
              )}

              <Col xs={8} style={{ display: "flex", justifyContent: "flex-end", gap: 6 }}>
                {headerRight}
              </Col>
            </Row>
          </div>

          {/* table */}
          <div style={{ padding: "12px 16px" }}>
            <Table
              rowKey="id"
              columns={mainColumns}
              dataSource={currentRows}
              rowSelection={{ selectedRowKeys, onChange: setSelectedRowKeys }}
              pagination={{
                current: pagination.current,
                pageSize: pagination.pageSize,
                total: pagination.total,
                showSizeChanger: true,
                onChange: (page, pageSize) =>
                  refetchActive({ [pageParam]: page, [pageSizeParam]: pageSize }),
              }}
              onChange={(pager, _filters, sorter) => {
                // optional: if you want server sorting with AntD
                if (sorter?.field) {
                  const dir = sorter.order === "ascend" ? "asc" : "desc";
                  refetchActive({ [sortParam]: sorter.field, [dirParam]: dir, [pageParam]: 1 });
                }
              }}
            />
          </div>
        </div>
      </div>

      {/* modal/drawer form */}
      {formNode}

      {/* inactive drawer (same UX) */}
      {enableInactiveDrawer && (
        <Drawer
          width={900}
          title={`Inactive ${title} (${inactivePagination.total})`}
          closable
          onClose={() => setInactiveDrawer(false)}
          open={inactiveDrawer}
          extra={
            <Input
              placeholder={`Search inactive ${title}`}
              allowClear
              value={inactiveSearchText}
              onChange={(e) => {
                const v = e.target.value;
                setInactiveSearchText(v);
                refetchInactive({ inactive_drawer: 1, inactive_page: 1, inactive_search: v });
              }}
              style={{ width: 240 }}
            />
          }
        >
          <Table
            rowKey="id"
            columns={inactiveColumns}
            dataSource={inactiveRows}
            pagination={{
              current: inactivePagination.current,
              pageSize: inactivePagination.pageSize,
              total: inactivePagination.total,
              showSizeChanger: true,
              onChange: (page, pageSize) =>
                refetchInactive({ inactive_drawer: 1, inactive_page: page, inactive_per_page: pageSize }),
            }}
          />
        </Drawer>
      )}
    </>
  );
}
