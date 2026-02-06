// resources/js/Layouts/AuthenticatedLayout.jsx
import React, { useState } from "react";
import { Layout, theme } from "antd";
import { usePage } from "@inertiajs/react";

import AppNavbar from "./partials/AppNavbar";
import AppSidebar from "./partials/AppSidebar";

const { Content } = Layout;

export default function AuthenticatedLayout({
  header,
  children,
  breadcrumb = [{ title: "Home" }],
  defaultOpenKeys = ["crm", "accounting"],
  siderWidth = 220,
}) {
  const NAV_HEIGHT = 64;

  const { props } = usePage();
  const user = props?.auth?.user;

  const {
    token: { colorBgContainer, borderRadiusLG },
  } = theme.useToken();

  const [openKeys, setOpenKeys] = useState(defaultOpenKeys);

  // TODO: wire route-based selection later
  const selectedKeys = ["home"];

  return (
    <Layout style={{ height: "100vh", overflow: "hidden" }}>
      {/* Fixed navbar */}
      <AppNavbar user={user} />

      {/* Everything below navbar */}
      <Layout
        style={{
          marginTop: NAV_HEIGHT,
          height: `calc(100vh - ${NAV_HEIGHT}px)`,
          overflow: "hidden",
        }}
        className="border-end"
      >
        {/* Sidebar: make it its own scroll container */}
        <div
          style={{
            width: siderWidth,
            height: "100%",
            overflowY: "auto",
            overflowX: "hidden",
            borderRight: "1px solid #f0f0f0",
            background: colorBgContainer,
          }}
        >
          <AppSidebar
            width={siderWidth}
            openKeys={openKeys}
            setOpenKeys={setOpenKeys}
            selectedKeys={selectedKeys}
            colorBgContainer={colorBgContainer}
          />
        </div>

        {/* Right side: header (fixed inside area) + content (scrollable) */}
        <Layout style={{ padding: 0, background: "#fafafa", overflow: "hidden" }}>
          {header ? (
            <div
              className="border-bottom px-2 pt-3 pb-2"
              style={{
                flex: "0 0 auto",
                background: "#fafafa",
              }}
            >
              {header}
            </div>
          ) : null}

          {/* Content area scrolls independently */}
          <div
            style={{
              flex: 1,
              overflowY: "auto",
              padding: 0,
            }}
          >
            <Content
              style={{
                margin: 0,
                minHeight: 280,
                background: colorBgContainer,
                borderRadius: borderRadiusLG,
              }}
            >
              {children}
            </Content>
          </div>
        </Layout>
      </Layout>
    </Layout>
  );
}
