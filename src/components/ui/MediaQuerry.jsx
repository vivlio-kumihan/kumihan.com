export const breakpoints = {
  sm: "480px",
  md: "600px",
  lg: "960px",
  xl: "1280px",
};

export const mq = {
  sm: `@media (min-width: ${breakpoints.sm})`,
  md: `@media (min-width: ${breakpoints.md})`,
  lg: `@media (min-width: ${breakpoints.lg})`,
  xl: `@media (min-width: ${breakpoints.xl})`,
};