"use client";

import {
  LineChart,
  Line,
  XAxis,
  Tooltip,
  ResponsiveContainer,
} from "recharts";
import { Card, CardContent } from "@/components/ui/card";
import { useState } from "react";

const data = [
  { date: "Apr 6", visitors: 120 },
  { date: "Apr 12", visitors: 210 },
  { date: "Apr 18", visitors: 160 },
  { date: "Apr 24", visitors: 300 },
  { date: "Apr 30", visitors: 280 },
  { date: "May 6", visitors: 320 },
  { date: "May 12", visitors: 400 },
  { date: "May 18", visitors: 380 },
  { date: "May 24", visitors: 420 },
  { date: "May 30", visitors: 460 },
  { date: "Jun 5", visitors: 480 },
  { date: "Jun 11", visitors: 520 },
  { date: "Jun 17", visitors: 560 },
  { date: "Jun 23", visitors: 590 },
  { date: "Jun 30", visitors: 620 },
];

export function OrdersChart() {
  const [range, setRange] = useState("3m");

  return (
    <Card>
      <CardContent className="p-6 space-y-6">
        {/* Header */}
        <div className="flex items-center justify-between">
          <div>
            <h3 className="font-semibold">Total Visitors</h3>
            <p className="text-sm text-muted-foreground">
              Total for the last 3 months
            </p>
          </div>

          {/* Filter */}
          <div className="flex rounded-lg border p-1 text-sm">
            {[
              { label: "Last 7 days", value: "7d" },
              { label: "Last 30 days", value: "30d" },
              { label: "Last 3 months", value: "3m" },
            ].map((item) => (
              <button
                key={item.value}
                onClick={() => setRange(item.value)}
                className={`rounded-md px-3 py-1 transition ${
                  range === item.value
                    ? "bg-primary text-primary-foreground"
                    : "text-muted-foreground hover:bg-muted"
                }`}
              >
                {item.label}
              </button>
            ))}
          </div>
        </div>

        {/* Chart */}
        <div className="h-72">
          <ResponsiveContainer width="100%" height="100%">
            <LineChart data={data}>
              <XAxis
                dataKey="date"
                tickLine={false}
                axisLine={false}
                className="text-xs"
              />
              <Tooltip />
              <Line
                type="monotone"
                dataKey="visitors"
                strokeWidth={3}
                dot={false}
              />
            </LineChart>
          </ResponsiveContainer>
        </div>
      </CardContent>
    </Card>
  );
}
