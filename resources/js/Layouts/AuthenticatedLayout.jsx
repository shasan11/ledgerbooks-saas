// resources/js/Layouts/AuthenticatedLayout.jsx
import React, { useState } from "react";
import { Layout, Breadcrumb, theme } from "antd";
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
  const { props } = usePage();
  const user = props?.auth?.user;

  const {
    token: { colorBgContainer, borderRadiusLG },
  } = theme.useToken();

  const [openKeys, setOpenKeys] = useState(defaultOpenKeys);

  // For now we hard-select "home" (you'll wire route-based selection later)
  const selectedKeys = ["home"];

  return (
    <Layout style={{ minHeight: "100vh" }}>
      <AppNavbar user={user} />

      <Layout>
        <AppSidebar
          width={siderWidth}
          openKeys={openKeys}
          setOpenKeys={setOpenKeys}
          selectedKeys={selectedKeys}
          colorBgContainer={colorBgContainer}
        />

        <Layout style={{ padding: "0 24px 24px" }}>
          {header ? (
            <div style={{ marginTop: 16, marginBottom: 8 }}>{header}</div>
          ) : null}

          <Breadcrumb items={breadcrumb} style={{ margin: "16px 0" }} />

          <Content
            style={{
              padding: 24,
              margin: 0,
              minHeight: 280,
              background: colorBgContainer,
              borderRadius: borderRadiusLG,
            }}
          >
            {children}
          </Content>
        </Layout>
      </Layout>
    </Layout>
  );
}
