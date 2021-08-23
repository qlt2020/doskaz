import set from 'lodash/set'

export default (errors) => {
    const violations = {};
    errors.forEach(e => set(violations, e.propertyPath, e.title))
    return violations;
}