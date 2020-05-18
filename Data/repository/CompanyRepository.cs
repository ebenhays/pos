using PointOfSale.Data.interfaces;
using PointOfSale.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace PointOfSale.Data.repository
{
	public class CompanyRepository : ICompany
	{
		private readonly POSContext _context = new POSContext();
		public void AddCompany(Company company)
		{
			if (company == null)
			{
				throw new ArgumentNullException(nameof(company));
			}

			var Savecompany = new Company()
			{
				Name = company.Name,
				CreationDate=DateTime.Now,
				CreatedBy=company.CreatedBy,
				ContactNo= company.ContactNo,
				Address= company.Address,
				EmailAddress= company.EmailAddress
			};
			_context.Companies.Add(Savecompany);
			_context.SaveChanges();
			
		}

		public void DeleteCompany(int id)
		{
			throw new NotImplementedException();
		}

		public IEnumerable<Company> GetCompanies()
		{
			throw new NotImplementedException();
		}

		public Company GetCompany(int id)
		{
			throw new NotImplementedException();
		}

		public void UpdateCompany(Company company)
		{
			throw new NotImplementedException();
		}
	}
}