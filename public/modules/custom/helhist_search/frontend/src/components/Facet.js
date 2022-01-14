import React from 'react';
import { Select } from 'hds-react/components/Select';

const Facet = ({ name, values, selectedValues, onFacetChange }) => {
  const titleMap = {
    "aggregated_phenomena_title": window.Drupal ? window.Drupal.t("Select phenomenon", {}, {context: "Search"}) : "Select phenomenon",
    "aggregated_formats_title": window.Drupal ? window.Drupal.t("Select format", {}, {context: "Search"}) : "Select format",
    "aggregated_neighbourhoods_title": window.Drupal ? window.Drupal.t("Select region", {}, {context: "Search"}) : "Select region"
  };

  return (
    <Select
      multiselect
      required
      label={titleMap[name]}
      placeholder={titleMap[name]}
      options={values}
      defaultValue={selectedValues}
      clearButtonAriaLabel={window.Drupal ? window.Drupal.t("Clear all selections", {}, {context: "Search"}) : "Clear all selections"}
      selectedItemRemoveButtonAriaLabel="Remove ${value}"
      onChange={(values) => onFacetChange(name, values)}
    />
  )
}

export default Facet;