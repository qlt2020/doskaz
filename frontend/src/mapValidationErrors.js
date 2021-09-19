import set from "lodash/set";

export default (errors) => {
  const violations = {};
  errors.forEach((e) => set(violations, e.property, e.message));
  return violations;
};
