import { Role } from "./role";

export function hasRole(userRole: Role, allowed: Role[]) {
  return allowed.includes(userRole);
}
