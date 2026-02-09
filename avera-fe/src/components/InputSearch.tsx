"use client"

import * as React from "react"

import {
  InputGroup,
  InputGroupAddon,
  InputGroupButton,
  InputGroupInput,
} from "@/components/ui/input-group"
import { SearchIcon } from "lucide-react"
export function InputSearchComponent() {

  return (
      <InputGroup>
        <InputGroupInput className="w-[50vw]" placeholder="Type to search..." />
        <InputGroupAddon align="inline-end">
          <InputGroupButton variant="secondary"><SearchIcon/></InputGroupButton>
        </InputGroupAddon>
      </InputGroup>
  )
}
