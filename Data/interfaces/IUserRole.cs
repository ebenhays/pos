using PointOfSale.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PointOfSale.Data.interfaces
{
	interface IUserRole
	{
		IEnumerable<UserRole> GetUserRoles();
		void AddUserRoles(UserRole userRole);
		UserRole GetUserRole(int id);
		void UpdateUserRole(UserRole userRole);
		void DeleteUserRole(int id);
	}
}
