using PointOfSale.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PointOfSale.Data.interfaces
{
	interface ICompany
	{
		IEnumerable<Company> GetCompanies();
		void AddCompany(Company company);
		Company GetCompany(int id);
		void UpdateCompany(Company company);
		void DeleteCompany(int id);
	}
}
